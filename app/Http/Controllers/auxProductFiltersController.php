<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use DB;
use Dashboard\Http\Requests;
use Dashboard\Models\Experimental\Color;
use Dashboard\Models\Experimental\Product;
use Dashboard\Models\Experimental\ProductStatus;
use Dashboard\Models\Experimental\Provider;
use Dashboard\Models\Experimental\Size;
use Dashboard\Models\Experimental\Type;
use Excel;
use Illuminate\Http\Request;
use Validator;
use Exception;

class auxProductFiltersController extends Controller
{

    /**
     * Restricion de metodos por nivel de usuario
     *
     */
    public function __construct()
    {
        $this->middleware("auth:GOD,NOS,ADM,JVE,STO", ["only" => ["FilterForAll"]]);
    }

    /**
     * Listado de tipos de produtos
     *
     *	@return  Illuminate\Http\Response
     */
    public function TypeProductList()
    {
        try {
            $types = Type::orderBy("name", "asc")->get();
            return response()->json(["types" => $types]);
        } catch (\Exception $e) {
            return response()->json(["message" => "Pascal mordio algunos cables, llame a sistemas"], 500);
        }
    }

    /**
     * Listado de proveedores
     *
     *	@return Illuminate\Http\Response
     */
    public function ProviderList()
    {
        try {
            $providers = Provider::select(array("id", "name"))->orderBy("name", "asc")->get();
            return response()->json(["providers" => $providers]);
        } catch (\Exception $e) {
            return response()->json(["message" => "Pascal mordio algunos cables, llame a sistemas"], 500);
        }
    }

    /**
     * Listado de productos | colores | tallas
     *
     *	@param  Illuminate\Http\Request  $request | string:name
     *	@return  Illuminate\Http\Response
     */
    public function ProductList(Request $request)
    {
        try {
            $data = array();
            $data["sizes"] =  Size::select(array("id", "name"))->orderBy("name", "asc")->get();
            $data["colors"] =  Color::select(array("id", "name"))->orderBy("name", "asc")->get();
            $data['products'] = Product::select("name","cost_provider","utility")->groupby("name")->orderBy("name", "asc")->get();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(["message" => "Pascal mordio algunos cables, llame a sistemas"], 500);
        }
    }

    /**
     * Listado de productos por filtro de busqueda
     *
     *	@param  Illuminate\Http\Request  $request
     *	@return  Illuminate\Http\Response
     */
    public function FilterForAll(Request $request)
    {
        $rules = [
            "type"            =>    "integer|exists:types,id",
            "provider_id"    =>    "integer|exists:providers,id",
            "product"         =>    "string|max:100",
            "color"           =>    "integer|exists:colors,id",
            "size"            =>    "integer|exists:sizes,id",
            "status"        =>  "integer|between:0,6",
            "status_sale"    =>    "integer|between:0,1"
        ];

        if (Validator::make($request->all(), $rules)->fails()) {
            return response()->json(["message" => "Parametros de busqueda no validos"], 401);
        }

        $query = DB::table('auxproducts as p')
                        ->select('p.status', DB::raw('DATE_FORMAT(p.created_at,\'%d-%m-%Y\') as date'), 'p.id', 'p.cod', 'p.name', 's.name as size', 'c.name as color', 'pv.name as provider', DB::raw('GROUP_CONCAT(t.name ORDER BY t.name ASC SEPARATOR \' - \') as types'), 'p.cost_provider', 'p.utility')
                        ->addSelect(DB::raw('p.cost_provider + p.utility  as price_real'))
                        ->addSelect(DB::raw('case when dc.price then dc.price else p.cost_provider + p.utility end as precio'))
                        ->addSelect(DB::raw('case when dc.price then 1 else 0 end liquidation'))
                        ->leftJoin('types_auxproducts as tp', 'tp.product_id', '=', 'p.id')
                        ->leftJoin('types as t', 't.id', '=', 'tp.type_id')
                        ->join('colors as c', 'c.id', '=', 'p.color_id')
                        ->join('sizes as s', 's.id', '=', 'p.size_id')
                        ->join('providers as pv', 'pv.id', '=', 'p.provider_id')
                        ->leftJoin('settlements as dc', 'dc.product_id', '=', 'p.id')
                        ->whereNull('p.deleted_at')
                        ->groupBy('cod')
                        ->orderBy('id', 'desc');

        $query = $this->QueryRequest($request, $query);

        $products = $query->get();

        $products = $this->StatusSalesCase($request, $products);

        return response()->json(['products' => $products], 200);
    }

    /**
     *    Descarga para filtro de kardex
     *
     *    @param  Illuminate\Http\Request  $request
     *    @return  ArrayBuffer
     */
    public function FilterForAllDownload(Request $request)
    {
        $rules = [
            "type"            =>    "integer|exists:types,id",
            "provider_id"    =>    "integer|exists:providers,id",
            "product"         =>    "string|max:100",
            "color"           =>    "integer|exists:colors,id",
            "size"            =>    "integer|exists:sizes,id",
            "status"        =>  "integer|between:0,3",
            "status_sale"    =>    "integer|between:0,1"
        ];

        if (Validator::make($request->all(), $rules)->fails()) {
            return response()->json(["message" => "Parametros de busqueda no validos"], 401);
        }

        $query = DB::table('auxproducts as p')
                        ->select('p.name as Nombre', 'p.status', 'p.cod as Codigo', 's.name as Talla', 'c.name as Color', 'pv.name as Proveedor', DB::raw('GROUP_CONCAT(t.name ORDER BY t.name ASC SEPARATOR \' - \') as Tipos'), 'p.cost_provider as Costo_proveedor', 'p.utility as Utilidad')
                        ->addSelect(DB::raw('p.cost_provider + p.utility as Precio_real'))
                        ->addSelect(DB::raw('case when dc.price then dc.price else p.cost_provider + p.utility end as Precio'))
                        ->addSelect(DB::raw('case when dc.price then 1 else 0 end liquidation'))
                        ->leftJoin('types_auxproducts as tp', 'tp.product_id', '=', 'p.id')
                        ->leftJoin('types as t', 't.id', '=', 'tp.type_id')
                        ->join('colors as c', 'c.id', '=', 'p.color_id')
                        ->join('sizes as s', 's.id', '=', 'p.size_id')
                        ->join('providers as pv', 'pv.id', '=', 'p.provider_id')
                        ->leftJoin('settlements as dc', 'dc.product_id', '=', 'p.id')
                        ->whereNull('p.deleted_at')
                        ->groupBy('cod')
                        ->orderBy('p.name', 'asc');

        $products = $query->get();

        $data = array();

        foreach ($products as $product) {
            if ($product->liquidation == 0) {
                $product->Venta = "normal";
            } elseif ($product->liquidation == 1) {
                $product->Venta = "liquidacion";
            } else {
                $product->Venta = "Otros";
            }

            if ($product->status == 0) {
                $product->Estado = "salida";
            } elseif ($product->status == 1) {
                $product->Estado = "disponible";
            } elseif ($product->status == 2) {
                $product->Estado = "vendido";
            } elseif ($product->status == 3) {
                $product->Estado = "reservado";
            } elseif ($product->status == 4) {
                $product->Estado = "observado";
            }

            unset($product->liquidation);
            unset($product->status);

            $data[] = (array)$product;
        }



        $excelSheet = Excel::create('kardex', function ($excel) use ($data) {
            $excel->sheet('Sheetname', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', true);
            });
        });

        return $excelSheet->download('xls');
    }

    /**
     *	Filtro de stock de productos para vendedores
     *
     *	@param  Illuminate\Http\Request  $request
     *	@return  Illuminate\Http\Response
     */
    public function FilterStockForAllByVEN(Request $request)
    {
        $query = Product::with('provider', 'types', 'color', 'size')
                ->select('provider_id', 'auxproducts.id', 'auxproducts.name', 'color_id', 'size_id', DB::raw('count(auxproducts.name) as cantP'), 'cost_provider', 'utility')
                ->addSelect(DB::raw('case when dc.price then 1 else 0 end liquidation'))
                ->leftJoin('types_auxproducts as tp', 'tp.product_id', '=', 'auxproducts.id')
                ->leftJoin('types as t', 't.id', '=', 'tp.type_id')
                ->join('colors as c', 'c.id', '=', 'auxproducts.color_id')
                ->join('sizes as s', 's.id', '=', 'auxproducts.size_id')
                ->join('providers as pv', 'pv.id', '=', 'auxproducts.provider_id')
                ->leftJoin('settlements as dc', 'dc.product_id', '=', 'auxproducts.id')
                ->where('status', '=', 1)
                ->groupby('name', 'color_id', 'size_id');

        $query = $this->QueryRequest($request, $query, 1);

        $stock = $query->get();

        $stock = $this->StatusSalesCase($request, $stock);

        foreach ($stock as $product) {
            if ($product->types) {
                $product->typesList = $product->types->implode("name", " | ");
            }

            $product->price_final = $product->cost_provider + $product->utility;
        }

        return response()->json(['stock' => $stock], 200);
    }

    /**
     *	Agregar consultas al filtro de  busqueda de productos 
     *	por parametros recibidos.
     *
     *	@param  Illuminate\Http\Request  $request
     *	@param  Illuminate\Support\Facades\DB  $query
     *	@return  Illuminate\Support\Facades\DB
     *	
     *	@param  Illuminate\Http\Request  $request
     *	@param  Illuminate\Database\Eloquent\Model  $query
     *	@return  Illuminate\Database\Eloquent\Model 	    
     */
    private function QueryRequest(Request $request, $query, $aux = null)
    {
        if ($request->has('order')) {
            $query->where('p.cod', request('order'));
        }

        if ($request->has('type')) {
            $query->where('tp.type_id', request('type'));
        }

        if ($request->has('provider_id')) {
            if ($aux) {
                $query->where('auxproducts.provider_id', request('provider_id'));
            } else {
                $query->where('p.provider_id', request('provider_id'));
            }
        }

        if ($request->has('product')) {
            if ($aux) {
                $query->where('auxproducts.name', request('product'));
            } else {
                $query->where('p.name', request('product'));
            }
        }

        if ($request->has('color')) {
            if ($aux) {
                $query->where('auxproducts.color_id', request('color'));
            } else {
                $query->where('p.color_id', request('color'));
            }
        }

        if ($request->has('size')) {
            if ($aux) {
                $query->where('auxproducts.size_id', request('size'));
            } else {
                $query->where('p.size_id', request('size'));
            }
        }

        if ($request->has('status')) {
            if ($aux) {
                $query->where('auxproducts.status', request('status'));
            } else {
                $query->where('p.status', request('status'));
            }
        }

        return $query;
    }

    /**
     *	Remover productos por tipo de estado de venta normal|liquidacion
     *
     *	@param  Illuminate\Http\Request  $request
     *	@param  Array  $data
     *	@return  Array
     *
     *	@param  Illuminate\Http\Request  $request
     *	@param  Collection  $data    
     *	@return  Collection
     */
    private function StatusSalesCase(Request $request, $data)
    {
        if ($request->has('status_sale')) {
            $statusSale = $request->input('status_sale');
            foreach ($data as $key => $value) {
                if ($value->liquidation != $statusSale) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }

    /**
     * Listado de productos por filtro de busqueda
     *
     *  @param  Illuminate\Http\Request  $request
     *  @return  Illuminate\Http\Response
     */
    public function FilterForSoftDelete(Request $request)
    {
        $end = Carbon::now();
        $start = $end->copy()->addDays(-7);

        // return [$start->toDateString(), $end->toDateString()];

        $query = DB::table('auxproducts as p')
                        ->select('p.status', DB::raw('DATE_FORMAT(p.created_at,\'%d-%m-%Y\') as date'), 'p.id', 'p.cod', 'p.name', 's.name as size', 'c.name as color', 'pv.name as provider', DB::raw('GROUP_CONCAT(t.name ORDER BY t.name ASC SEPARATOR \' - \') as types'), 'p.cost_provider', 'p.utility', 'p.deleted_at')
                        ->addSelect(DB::raw('p.cost_provider + p.utility  as price_real'))
                        ->addSelect(DB::raw('case when dc.price then dc.price else p.cost_provider + p.utility end as precio'))
                        ->addSelect(DB::raw('case when dc.price then 1 else 0 end liquidation'))
                        ->leftJoin('types_auxproducts as tp', 'tp.product_id', '=', 'p.id')
                        ->leftJoin('types as t', 't.id', '=', 'tp.type_id')
                        ->join('colors as c', 'c.id', '=', 'p.color_id')
                        ->join('sizes as s', 's.id', '=', 'p.size_id')
                        ->join('providers as pv', 'pv.id', '=', 'p.provider_id')
                        ->leftJoin('settlements as dc', 'dc.product_id', '=', 'p.id')
                        ->where('p.deleted_at', '<>', null)
                        ->where('p.deleted_at', '>=', $start)
                        ->where('p.deleted_at', '<=', $end)
                        ->groupBy('cod')
                        ->orderBy('id', 'desc');

        $products = $query->get();

        $products = $this->StatusSalesCase($request, $products);

        return $products;
    }

    public function getProductStatus()
    {
        $statuses = ProductStatus::all();

        return ["statuses" => $statuses];
    }

    public function getProductStatusIndicator(Request $request, $id)
    {
        try {
            $rules = [
                "year" => "integer|between:2016,2020",
                "month" => "integer|between:1,12"
            ];

            if (Validator::make($request->all(), $rules)->fails()) {
                return response()->json(["message" => "Parametros de busqueda invalidos"], 423);
            }

            $indicator = $this->setDateSearch($request, $id);

            $total = 0;
            if (count($indicator) > 0) {
                foreach ($indicator->status_details as $row) {
                    $total += $row->detail_statuses_count;
                }
            }

            $chart = array();
            if (count($indicator) > 0) {
                foreach ($indicator->status_details as $i => $detail) {
                    $chart["data"][] = $detail->detail_statuses_count;
                    $chart["labels"][] = $detail->description;
                    $chart["colors"][] = $this->getColor($i);
                }
            }

            return response()->json(["indicator" => $indicator, "chart" => $chart]);
        } catch (Exception $e) {
            return response()->json(["message" => "Unos gatos ninja estan jugando con los cables"], 500);
        }
    }

    private function getColor($i = 0)
    {
        $colors = ["#4CAF50", "#FFEB3B", "#f44336", "#3F51B5", "#9C27B0", "#FF5722", "#00BCD4", "#009688"];
        return ($i > 7)?$colors[0]:$colors[$i];
    }

    private function setDateSearch(Request $request, $id)
    {
        $start = $end = null;
        if ($request->has("year") && $request->has("month")) {
            $start = Carbon::createFromDate(request("year"), request("month"), 1)->setTime(0, 0, 0);
            $end = $start->copy()->addMonth();
        } elseif ($request->has("year")) {
            $start = Carbon::createFromDate(request("year"), 1, 1)->setTime(0, 0, 0);
            $end = $start->copy()->addMonth(12);
        } elseif ($request->has("month")) {
            $now = Carbon::now();
            $start = Carbon::createFromDate($now->year, request("month"), 1)->setTime(0, 0, 0);
            $end = $start->copy()->addMonth();
        }

        if ($start && $end) {
            $query = ProductStatus::with(["status_details" => function ($query) use ($start, $end) {
                            return $query->has("DetailStatusesCount")
                                    ->with(["DetailStatusesCount" => function ($query) use ($start, $end) {
                                        return $query->where("created_at", ">=", $start)
                                                    ->where("created_at", "<", $end);
                                    }]);
                    }])->where("id", $id);
        } else {
            $query = ProductStatus::with(["status_details" => function ($query) {
                            return $query->has("DetailStatusesCount")
                                    ->with("DetailStatusesCount");
                    }])->where("id", $id);
        }

        $indicator = $query->first();

        return $this->helperIndicator($indicator);
    }

    private function helperIndicator($data)
    {
        foreach ($data->status_details as $i => $row) {
            if ($row->detail_statuses_count == null) {
                $data->status_details->splice($i, 1);
                return $this->helperIndicator($data);
            }
        }
        return $data;
    }
}
