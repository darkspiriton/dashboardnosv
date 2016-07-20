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

class auxProductFiltersController extends Controller
{

    /**
     * Restricion de metodos por nivel de usuario
     *
     */
    public function __construct()
    {
        $this->middleware("auth:GOD,ADM,JVE", ["only" => ["FilterForAll"]]);
    }

    /**
     * Listado de tipos de produtos
     *
     *	@return Illuminate\Http\Response 		Dashboard\Models\Experimental\Type
     */
    public function TypeProductList()
    {
        try {
            $types = Type::all();
            return response()->json(["types" => $types]);
        } catch (\Exception $e) {
            return response()->json(["message" => "Pascal mordio algunos cables, llame a sistemas"], 500);
        }
    }

    /**
     * Listado de proveedores
     *
     *	@return Illuminate\Http\Response 		Dashboard\Models\Experimental\Provider
     */
    public function ProviderList()
    {
        try {
            $providers = Provider::get(array("id", "name"));
            return response()->json(["providers" => $providers]);
        } catch (\Exception $e) {
            return response()->json(["message" => "Pascal mordio algunos cables, llame a sistemas"], 500);
        }
    }

    /**
     * Listado de productos | colores | tallas
     *
     *	@param 	Illuminate\Http\Request 	$request | string:name		
     *	@return Illuminate\Http\Response 	Dashboard\Models\Experimental\Color
     *	@return Illuminate\Http\Response	Dashboard\Models\Experimental\Size
     *
     *	@param 	Illuminate\Http\Request		$request | int:provider_id
     *	@return Illuminate\Http\Response	Dashboard\Models\Experimental\Product
     */
    public function ProductList(Request $request)
    {
        try {
            if ($request->has("name")) {
                $colors = Product::select("color_id")->distinct()->where("name", $request->input("name"))->lists("color_id");
                $sizes = Product::select("size_id")->distinct()->where("name", $request->input("name"))->lists("size_id");

                $data = array();
                $data["sizes"] =  Size::select("id", "name")->whereIn("id", $sizes)->get();
                $data["colors"] =  Color::select("id", "name")->whereIn("id", $colors)->get();

                return response()->json($data);
            } else {
                $query = Product::select("name")->distinct()->orderBy("name", "asc");

                if ($request->has("provider_id")) {
                    $query->where("provider_id", $request->input("provider_id"));
                }

                $products = $query->get();

                return response()->json(["products" => $products]);
            }
        } catch (\Exception $e) {
            return response()->json(["message" => "Pascal mordio algunos cables, llame a sistemas"], 500);
        }
    }

    /**
     * Listado de productos por filtro de busqueda
     *
     *	@param 	Illuminate\Http\Request 	$request
     *	@return Illuminate\Http\Response 	Illuminate\Support\Facades\DB
     */
    public function FilterForAll(Request $request)
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

        $products = $query->get();

        if ($request->has('status_sale')) {
            $statusSale = $request->input('status_sale');
            foreach ($products as $key => $value) {
                if ($value->liquidation != $statusSale) {
                    unset($products[$key]);
                }
            }
        }

        return response()->json(['products' => $products], 200);
    }
}
