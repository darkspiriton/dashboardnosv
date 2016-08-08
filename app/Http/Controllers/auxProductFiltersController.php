<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Requests;
use Dashboard\Models\Experimental\Color;
use Dashboard\Models\Experimental\Product;
use Dashboard\Models\Experimental\Provider;
use Dashboard\Models\Experimental\Size;
use Dashboard\Models\Experimental\Type;
use Illuminate\Http\Request;
use \Validator;
use \DB;
use \Log;

class auxProductFiltersController extends Controller
{

    /**
     * Restricion de metodos por nivel de usuario
     *
     */
    public function __construct()
    {
        $this->middleware("auth:GOD,ADM,JVE,STO", ["only" => ["FilterForAll"]]);
    }

    /**
     * Listado de tipos de produtos
     *
     *	@return  Illuminate\Http\Response
     */
    public function TypeProductList()
    {
        try {
            $types = Type::orderBy("name","asc")->get();
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
            $providers = Provider::select(array("id", "name"))->orderBy("name","asc")->get();
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
                $data["sizes"] =  Size::select(array("id", "name"))->orderBy("name","asc")->get();
                $data["colors"] =  Color::select(array("id", "name"))->orderBy("name","asc")->get();
                $data['products'] = Product::select("name")->distinct()->orderBy("name", "asc")->get();

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
            "status"        =>  "integer|between:0,4",
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
                        ->groupBy('cod')
                        ->orderBy('id', 'desc');

        $query = $this->QueryRequest($request, $query);

        $products = $query->get();

        $products = $this->StatusSalesCase($request, $products);

        foreach ($products as $product) {
            if($product->liquidation == 0){
                $product->status_sale = "normal";
            } else if($product->liquidation == 1){
                $product->status_sale = "liquidacion";
            } else {
                $product->status_sale = "Otros";
            }

            if($product->status == 0){
                $product->status_prd = "salida";
            } else if($product->status == 1){
                $product->status_prd = "disponible";
            } else if($product->status == 2){
                $product->status_prd = "vendido";
            } else if($product->status == 3){
                $product->status_prd = "reservado";
            } else if($product->status == 4){
                $product->status_prd = "observado";
            }
        }
        $collect = collect($products);
        $collect->sortBy("name");

        $data = $collect->toArray();

        $date = date('Y-m-d');

        $title = 'Reporte de kardex para el día: '.$date;

        $columns = ['Fecha' => 'date',
            'Codigo' => 'cod',
            'Nombre' => 'name',
            'Proveedor' => 'provider',
            'Talla' => 'size',
            'Color' => 'color',
            'Tipos' => 'types',
            'Estado V.' => 'status_sale',
            'Precio R.' => 'price_real',
            'Precio.' => 'precio',
            'Estado' => 'status_prd',
        ];

        $view =  \View::make('pdf.templatePDF', compact('data', 'columns', 'title', 'date'))->render();

        $pdf = \PDF::loadHTML($view);
        $pdf->setOrientation('landscape');

        return $pdf->download();
    }

    /**
     *	Filtro de stock de productos para vendedores
     *
     *	@param  Illuminate\Http\Request  $request
     *	@return  Illuminate\Http\Response
     */	
    public function FilterStockForAllByVEN(Request $request){
    	$query = Product::from( 'auxproducts as p' )
    			->with('provider','types','color','size')
    	        ->select('provider_id','p.id','p.name','color_id','size_id',DB::raw('count(p.name) as cantP'),'cost_provider','utility')
    	        ->addSelect(DB::raw('case when dc.price then 1 else 0 end liquidation'))
    	        ->leftJoin('types_auxproducts as tp', 'tp.product_id', '=', 'p.id')
    	        ->leftJoin('types as t', 't.id', '=', 'tp.type_id')
    	        ->join('colors as c', 'c.id', '=', 'p.color_id')
    	        ->join('sizes as s', 's.id', '=', 'p.size_id')
    	        ->join('providers as pv', 'pv.id', '=', 'p.provider_id')
    	        ->leftJoin('settlements as dc', 'dc.product_id', '=', 'p.id')
    	        ->where('status', '=', 1)
    	        ->groupby('name','color_id','size_id');

    	$query = $this->QueryRequest($request, $query);

    	$stock = $query->get();

    	$stock = $this->StatusSalesCase($request, $stock);

    	foreach ($stock as $product) {
    	    if ($product->types){
    	        $product->typesList = $product->types->implode("name"," | ");
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
    private function QueryRequest(Request $request, $query){
    	if ($request->has('type')) {
    	    $query->where('tp.type_id', $request->input('type'));
    	}

    	if ($request->has('provider_id')) {
    	    $query->where('p.provider_id', $request->input('provider_id'));
    	}

    	if ($request->has('product')) {
    	    $query->where('p.name', $request->input('product'));
    	}

    	if ($request->has('color')) {
    	    $query->where('p.color_id', $request->input('color'));
    	}

    	if ($request->has('size')) {
    	    $query->where('p.size_id', $request->input('size'));
    	}

    	if ($request->has('status')) {
    	    $query->where('p.status', $request->input('status'));
    	}

    	if ($request->has('status')) {
    	    $query->where('p.status', $request->input('status'));
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
    private function StatusSalesCase(Request $request, $data){
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
}
