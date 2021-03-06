<?php

namespace Dashboard\Http\Controllers;

use DB;
use Exception;
use Carbon\Carbon;
use Dashboard\User;
use Dashboard\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Dashboard\Events\ProductWasCreated;
use Dashboard\Models\Experimental\Size;
use Dashboard\Models\Experimental\Type;
use Dashboard\Events\NotificationPusher;
use Dashboard\Models\Experimental\Alarm;
use Dashboard\Models\Experimental\Color;
use Dashboard\Models\Experimental\Product;
use Dashboard\Models\Experimental\Provider;
use Dashboard\Events\ProductStatusWasChanged;
use Faker\Test\Provider\ProviderOverrideTest;
use Dashboard\Models\Experimental\ProductDetailStatus;
use Dashboard\Models\Experimental\ProductStatusDetail;
use Excel;


class AuxProductController extends Controller
{

    /**
     * AuxProductController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:GOD,NOS,ADM,JVE,PUB,PRO,VEN,STO');
        $this->middleware('auth:GOD,NOS,ADM,JVE,STO', ['only' => 'movements_for_product']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

        if ($request->has('search')) {
            $query->where('p.name', 'like', '%'.$request->input('search').'%');
        }

        $products = $query->get();

        return response()->json(['products' => $products], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Creamos las reglas de validación
        $rules = [
            'cod'           => 'required|integer',
            'provider_id'   => 'required|integer|exists:providers,id',
            'color_id'      => 'required|integer|exists:colors,id',
            'size_id'       => 'required|integer|exists:sizes,id',
            'name'          => 'required|max:100',
            'types'         => 'required|array',
            'types.*.id'    => 'required|integer',
            'alarm.day'           => 'required|integer',
            'alarm.count'         => 'required|integer',
            'cant'          => 'required|integer',
            'cost'          => 'required|numeric',
            'uti'          => 'required|numeric',
        ];

        //Se va a pasar datos del producto, attributos y su cantidad
        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesarios para crear un producto'], 401);
            }

            $product = DB::table('auxproducts')
                        ->where('cod', '=', $request->input('cod'))
                        ->get();
            if ($product == null) {
                $data['day'] = $request->input('day');
                $data['count'] = $request->input('count');

                $data['provider_id'] = $request->input('provider_id');
                $data['color_id'] = $request->input('color_id');
                $data['size_id'] = $request->input('size_id');
                $data['name'] = $request->input('name');
                $data['cant'] = $request->input('cant');
                $data['cod'] = $request->input('cod');
                $data['cost']=$request->input('cost');
                $data['uti']=$request->input('uti');
                $data['day']=$request->input('alarm.day');
                $data['count']=$request->input('alarm.count');



                $types = $request->input('types');

                // Si el validador pasa, almacenamos la alarma
                $alarm = new Alarm();
                $alarm->day = $data['day'];
                $alarm->count = $data['count'];
                $alarm->save();

                $cant=$data['cant'];
                $cod= $data['cod'];
                for ($i=0;$i<$cant;$i++) {
                    $product = new Product();
                    $product->cod= $cod+$i;
                    $product->provider_id= $data['provider_id'];
                    $product->color_id= $data['color_id'];
                    $product->size_id= $data['size_id'];
                    $product->alarm_id= $alarm->id;
                    $product->name= strtoupper($data['name']);
                    $product->status= 1;
                    $product->cost_provider=$data['cost'];
                    $product->utility=$data['uti'];
                    $product->save();

                    foreach ($types as $type) {
                        $tipo = Type::find($type['id']);
                        $product->types()->attach($tipo);
                    }
                }

                //Event::fire(new ProductWasCreated($data));

                return response()->json(['message' => 'El producto se agrego correctamente'], 200);
            } else {
                return response()->json(['message' => 'El código del producto ya existe'], 401);
            }
        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // try {
            $product = Product::with('types', 'alarm')
                            ->select(array('id', 'cod', 'provider_id', 'color_id', 'size_id', 'name', 'cost_provider as cost', 'utility as uti', 'cost_provider', 'utility', 'alarm_id'))
                            ->find($id);

            if ($product !== null) {
                return response()->json(['product' => $product], 200);
            }

            return \Response::json(['message' => 'No existe ese producto'], 404);
        // } catch (Exception $e) {
        //     return \Response::json(['message' => 'Ocurrio un error'], 500);
        // }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'id'           => 'required|integer',
            'cod'           => 'required|integer',
            'provider_id'   => 'required|integer|exists:providers,id',
            'color_id'      => 'required|integer|exists:colors,id',
            'size_id'       => 'required|integer|exists:sizes,id',
            'name'          => 'required|max:100',
            'types'         => 'required|array',
            'types.*.id'    => 'required|integer',
            'cost'          => 'required|numeric',
            'uti'          => 'required|numeric',
            'alarm'         => 'required|array',
            'alarm.day'           => 'required|integer',
            'alarm.count'         => 'required|integer',
        ];

        try {
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesarios para actualizar un producto y/o el codigo de producto ya existe'], 401);
            }

            if (Product::select(DB::raw('count(*)'))->where('cod', '=', $request->input('cod'))->where('id', '<>', $request->input('id'))->count() > 0) {
                return response()->json(['message' => 'El código ya esta en uso'], 401);
            }

            $product = Product::find($id);

            if ($product->exists()) {
                response()->json(['message' => 'El producto no existe', 404]);
            }

            $product->cod = $request->input('cod');
            $product->provider_id = $request->input('provider_id');
            $product->color_id = $request->input('color_id');
            $product->size_id = $request->input('size_id');
            $product->name = $request->input('name');
            $product->cost_provider = $request->input('cost');
            $product->utility = $request->input('uti');
            $product->save();

//            $products_ids = Product::where('name','=',$product->name)->where('color_id','=',$product->color_id)
//                ->where('size_id','=',$product->size_id)->get()->lists('alarm_id');


            Alarm::where('id', $product->alarm_id)->update(['day' => $request->input('alarm.day'), 'count' => $request->input('alarm.count')]);

            $types = array();
            foreach ($request->input('types') as $i => $type) {
                $types[$i] = $type["id"];
            }

            $product->types()->sync($types);

            return response()->json(['message' => 'Producto editado correctamente']);
        } catch (Exception $e) {
            return \Response::json(['message' => 'Ocurrio un error al editar producto'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $auxproduct = Product::find($id);

        if ($auxproduct != null){
            $product = Product::with(["movements", "publicities", "settlement", "color", "size"])->find($id);

            if ($product->movements->count() > 0) {
                return response()->json(['message'=>'No se puede eliminar este producto porque posee movimiento asociados'], 401);
            } elseif ($product->publicities->count() > 0) {
                return response()->json(['message'=>'No se puede eliminar este producto tiene publicidad asociada'], 401);
            } elseif ($product->settlement !== null) {
                return response()->json(['message'=>'No se puede eliminar este producto esta en liquidacion'], 401);
            } else {
                // $product->types()->detach();
                $product->delete();

                $user_id = request("user")["sub"];
                $user = User::find($user_id);

                $body = "Usuario: ".$user->first_name
                            ." Elimino el codigo: ".$product->cod
                                .", modelo: ".$product->name
                                    .", color: ".$product->color->name
                                        .", size: ".$product->size->name;

                event(new NotificationPusher("Eliminacion de producto", $body, 2, "productDelete"));

                return response()->json(['message'=>'Se elimino el producto correctamente'], 200);
            }
        } else {
            return response()->json(['message'=>'El producto selecciona no existe o ya fue elimiando']);
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);

        if ($product == null) {
            return response()->json(["message" => "El producto a restaurar no existe."]);
        }

        if ($product->trashed()) {
            $product->restore();


            $user_id = request("user")["sub"];
            $user = User::find($user_id);

            $body = "Usuario: ".$user->first_name
                        ." Restauro el codigo: ".$product->cod
                            .", modelo: ".$product->name;

            event(new NotificationPusher("Restauracion de producto", $body, 4, "productRestore"));

            return response()->json(["message" => "El producto se restauro con exito."]);
        } else {
            return response()->json(["message" => "El producto no se encuentra eliminado."]);
        }
    }


    public function setProvider(Request $request)
    {
        // Creamos las reglas de validación
        $rules = [
            'name'          => 'required',
        ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un nuevo proveedor'], 401);
            }

            $provider = Provider::where('name', '=', $request->input('name'))->exists();

            if (!$provider) {
                $types = json_decode($request->input('types'), true);

                $user = new User();
                $user->first_name= $request->input('name');
                $user->last_name= 'Proveedor';
                $user->email= strtolower($request->input('name')).'@nosvenden.com';
//                $user->phone= $request->input('phone');
//                $user->address= $request->input('address');
//                $user->sex= $request->input('sex');
                $user->role_id= 8;
                $user->status= true;
                //$user->user= $request->input('user');
                $user->password= bcrypt(strtolower($request->input('name')));
                $user->save();

                $proveedor = new Provider();
                $proveedor->name = ucwords($request->input('name'));
                $proveedor->idUser= $user->id;
                $proveedor->save();
                return response()->json(['message' => 'El nuevo proveedor se agrego correctamente'], 200);
            } else {
                return response()->json(['message' => 'El nombre del proveedor ya existe'], 401);
            }
        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar proveedor'], 500);
        }
    }

    public function setColor(Request $request)
    {
        // Creamos las reglas de validación
        $rules = [
            'name'          => 'required',
        ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['message' => 'No posee todo los campos necesario para crear un nuevo color'], 401);
            }

            $color = Color::where('name', '=', $request->input('name'))->exists();

            if (!$color) {
                $c = new Color();
                $c->name = ucwords($request->input('name'));
                $c->save();
                return response()->json(['message' => 'El nuevo color se agrego correctamente'], 200);
            } else {
                return response()->json(['message' => 'El nombre del color ya existe'], 401);
            }
        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            return \Response::json(['message' => 'Ocurrio un error al agregar color'], 500);
        }
    }

    public function getCod()
    {
        $codigos = DB::table('auxproducts')
            ->select('cod')
            ->orderby('cod', 'asc')
            ->get();
        $i=0;
        $j=0;

        if ($codigos != null) {
            $data = array();
            foreach ($codigos as $codigo) {
                if ($codigo->cod - $i != 1) {
                    if ($i !=0) {
                        $cant = $codigo->cod - $i;
                        $codAux = $i;
                        for ($z = 1; $z < $cant; $z++) {
                            $data[$j] = $codAux + $z;
                            $j++;
                        }
                    } else {
                        $cod=$codigo->cod;
                        if ($cod!=1) {
                            for ($p = 1; $p < $cod; $p++) {
                                $data[$j] = $p;
                                $j++;
                            }
                        }
                    }
                }
                $i=$codigo->cod;
            }
            if (count($codigo) == 0) {
                $data[] = 1;
            } else {
                $data[] = $codigo->cod + 1;
            }


            return response()->json(['codes' => $data], 200);
        } else {
            return response()->json(['message' => 'No hay productos'], 200);
        }
    }

    public function cantPro()
    {
        $cant = DB::table('providers as pr')
            ->select('pr.name', DB::raw('count(p.id) as cant'))
            ->join('auxproducts as p', 'pr.id', '=', 'p.provider_id')
            ->where('p.status', 1)
            ->groupby('pr.name')->get();
        
        return response()->json(['products'=>$cant], 200);
    }

    public function stockProd()
    {
        $stock = Product::with('provider', 'types', 'color', 'size')
                ->select('provider_id', 'id', 'name', 'color_id', 'size_id', DB::raw('count(name) as cantP'), 'cost_provider', 'utility')
                ->where('status', '=', 1)
                ->groupby('name', 'color_id', 'size_id')->get();

        $resume = Product::with('provider', 'types')
                    ->select('created_at as create', 'provider_id', 'id', 'name', DB::raw('count(name) as cantP'), 'cost_provider', 'utility')
                    ->where('status', '=', 1)
                    ->orWhere('status', '=', 3)
                    ->groupby('name')->get();

        foreach ($stock as $product) {
            if ($product->types) {
                $product->typesList = $product->types->implode("name", " | ");
            }

            $product->price_final = $product->cost_provider + $product->utility;
        }

        foreach ($resume as $product) {
            if ($product->types) {
                $product->typesList = $product->types->implode("name", " | ");
            }
            $product->price_final = $product->cost_provider + $product->utility;
        }

        return response()->json(['stock' => $stock, 'resume' => $resume], 200);
    }

    public function FilterForStockDownload()
    {
        $products = Product::with('provider', 'types')
                    ->select('created_at as Fecha_Creación', 'provider_id', 'id', 'name as Producto', DB::raw('count(name) as cantidad'),'cost_provider','utility')
                    ->where('status', '=', 1)
                    ->orWhere('status', '=', 3)
                    ->groupby('name')->get();
        $data = array();


        foreach ($products as $product) {
            if ($product->types) {
                $product->typesList = $product->types->implode("name", " | ");
            }
            $product->price_final = $product->cost_provider + $product->utility;
            $product->provider_id=$product->provider->name;


            unset($product->provider);
            unset($product->types);

            $data[]=(array)$product->toArray();
        }

        // return $data;

        $excelSheet = Excel::create('stock', function ($excel) use ($data) {
            $excel->sheet('Sheetname', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', true);
            });
        });

        return $excelSheet->download('xls');
    }

    public function stockProdType($id)
    {
        $types = DB::table('auxproducts as p')
            ->select(DB::raw('GROUP_CONCAT(t.name ORDER BY t.name ASC SEPARATOR \' - \') as types'))
            ->join('types_auxproducts as tp', 'tp.product_id', '=', 'p.id')
            ->join('types as t', 't.id', '=', 'tp.type_id')
            ->where('p.id', '=', $id)
            ->groupBy('cod')
            ->first();

        return response()->json($types, 200);
    }

    public function stockIni()
    {
        $stock = DB::table('auxproducts as p')
                ->select(DB::raw('count(p.name)'))
                ->where('p.status', '=', '1')->get();

        return response()->json(['stock'=>$stock], 200);
    }

    public function prodSize()
    {
        $productSize = DB::table('sizes as s')
                ->select('p.name', 's.name as size', DB::raw('count(p.size_id) as cant'))
                ->join('auxproducts as p', 's.id', '=', 'p.size_id')
                ->where('p.status', '=', '1')
                ->groupby('p.name', 's.name')->get();

        return response()->json(['products'=>$productSize], 200);
    }

    public function prodColor()
    {
        $productColor = DB::table('colors as c')
            ->select('p.name', 'c.name as color', DB::raw('count(p.color_id) as cant'))
            ->join('auxproducts as p', 'c.id', '=', 'p.color_id')
            ->where('p.status', '=', '1')
            ->groupby('p.name', 'c.name')->get();
        return response()->json(['products'=>$productColor], 200);
    }

    public function prodOutProvider()
    {
        $productOutProvider = DB::table('providers as pr')
            ->select('pr.name as provider', 'p.name', DB::raw('count(m.id) as cant'))
            ->join('auxproducts as p', 'pr.id', '=', 'p.provider_id')
            ->join('auxmovements as m', 'p.id', '=', 'm.product_id')
            ->groupby('pr.name', 'p.name')
            ->orderby('cant', 'desc')->get();

        return response()->json(['products' => $productOutProvider], 200);
    }
    
    public function getProviders()
    {
        $providers = Provider::orderBy('name', 'asc')->get();
        return response()->json(['providers' => $providers ], 200);
    }

    public function getSizes()
    {
        $sizes = Size::all();
        return response()->json(['sizes' => $sizes], 200);
    }

    public function getColors()
    {
        $colors = Color::orderBy('name', 'asc')->get();
        return response()->json(['colors' => $colors], 200);
    }

    public function alarm()
    {
        $products = DB::table('alarms as a')
                ->select('p.name', 'c.name as color', 's.name as talla', 'p.created_at', 'a.day', 'a.count', DB::raw('count(p.id) as cant'))
                ->join('auxproducts as p', 'a.id', '=', 'p.alarm_id')
                ->join('auxmovements as m', 'p.id', '=', 'm.product_id')
                ->join('colors as c', 'c.id', '=', 'p.color_id')
                ->join('sizes as s', 's.id', '=', 'p.size_id')
                ->where('m.status', '=', 'vendido')
                ->groupby('p.name')->get();

//        return $products;

        $j=0;
        $alarms = array();

        foreach ($products as $product) {
            $dateNow=Carbon::now();
            $date=Carbon::createFromFormat('Y-m-d H:i:s', $product->created_at);

            $diff=(int)$dateNow->diffInDays($date);

            if ($diff > (int)$product->day) {
                if ((int)$product->cant < (int)$product->count) {
                    $alarms[$j] = array('name' => $product->name);
                    $j++;
                }
            }
        }
        if (!empty($alarms)) {
            return response()->json([ 'alarms' => $alarms ], 200);
        } else {
            return response()->json([ 'message' => 'Por el momento no hay alarmas' ], 404);
        }
    }

    public function listProduct(Request $request)
    {
        // try {
            if ($request->has("name")) {
                $colors = Product::select("color_id")->distinct()->where("name", $request->input("name"))->lists("color_id");
                $sizes = Product::select("size_id")->distinct()->where("name", $request->input("name"))->lists("size_id");

                $data = array();
                $data["sizes"] =  Size::select("id", "name")->whereIn("id", $sizes)->get();
                $data["colors"] =  Color::select("id", "name")->whereIn("id", $colors)->get();

                return response()->json($data);
            } else {
                $products = Product::select("name","cost_provider","utility")->distinct()->orderBy("name", "asc")->get();
                return response()->json(["products" => $products]);
            }
        // } catch (\Exception $e) {
        //     return \Response::json(["message" => "Se cruzaron algunos cables, llame a sistemas"], 500);
        // }
    }

    public function UniqueProduct()
    {
        try {
            $products = Product::select(array('name','cost_provider','utility'))->groupBy('name')->get();

            return response()->json(['products' => $products], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Se cruzaron algunos cables, llame a sistemas'], 500);
        }
    }

    public function CodesLists($name)
    {
        try {
            $products = \DB::table('auxproducts AS p')
                ->select(array('p.id', 'p.name as n', \DB::raw('concat(p.cod," - ",p.name," - ",c.name," - ",s.name) as name')))
                ->join('colors AS c', 'c.id', '=', 'p.color_id')
                ->join('sizes AS s', 's.id', '=', 'p.size_id')
                ->where('p.status', '=', 1)
                ->where('p.name', '=', $name)
                ->get();

            if (count($products) == 0) {
                return response()->json(['message' => 'No hay productos en existencia'], 404);
            }

            return response()->json(['codes' => $products], 200);
        } catch (\Exception $e) {
            return \Response::json(['message' => 'Ocurrio un error al agregar producto'], 500);
        }
    }

    public function productProvider(Request $request, $id)
    {
        if ($request->has('date1')) {
            try {
                $date1 = Carbon::createFromFormat('Y-m-d', $request->input('date1'));
            } catch (\InvalidArgumentException $e) {
                return response()->json(['message' => 'Fechas no validas, formato aceptado: Y-m-d'], 401);
            }
        } else {
            $date1 = Carbon::today();
        }

        $date2 = $date1->copy()->addDay();
        $movements=$this->movementsGet($date1, $date2, $id);
        return response()->json(['movements' => $movements], 200);
    }

    public function productProviderMonth(Request $request, $id)
    {
        $rules = [
            'year' => 'required|integer',
            'month' => 'required|integer'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message' => 'No posee todo los campos necesarios para consultar proveedores por meses'], 401);
        }

        $year=$request->input('year');
        $month=$request->input('month');

        $date1=Carbon::create($year, $month, 1, 0, 0, 0, 'America/Lima');
        $date2 = $date1->copy()->addMonth();

        $movements=$this->movementsGet($date1, $date2, $id);
        return response()->json(['movements' => $movements], 200);
    }

    public function productProviderDate(Request $request, $id)
    {
        try {
            $date1 = Carbon::createFromFormat('Y-m-d', $request->input('dateaux1'));
            $dateaux2 = Carbon::createFromFormat('Y-m-d', $request->input('dateaux2'));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => 'Fechas no validas, formato aceptado: Y-m-d'], 401);
        }

        $date2 = $dateaux2->copy()->addDay();
        $movements=$this->movementsGet($date1, $date2, $id);
        return response()->json(['movements' => $movements], 200);
    }

    public function productProviderTotalMonth(Request $request, $id)
    {
        $rules =[
            'year' => 'required|integer',
            'month' => 'required|integer'
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['message', 'No posee todo los campos necesarios para consultar ventas de proveedores']);
        }

        $sales = $this->saleMonth($request->input('year'), $request->input('month'), $id);

        return $sales;
    }

    public function productProviderTotalMonthNow($id)
    {
        $date1=Carbon::today();
        $sales = $this->saleMonth($date1->year, $date1->month, $id);

        return $sales;
    }

    private function saleMonth($year, $month, $id)
    {
        $date1=Carbon::create($year, $month, 1, 0, 0, 0, 'America/Lima');
        $date2 = $date1->copy()->addMonth();


        $status = 'vendido';
        $movements=DB::table('auxproducts as p')
            ->select('m.date_shipment as fecha', DB::raw('count(p.name) as cantidad'))
            ->join('auxmovements as m', 'p.id', '=', 'm.product_id')
            ->join('colors as c', 'c.id', '=', 'p.color_id')
            ->join('sizes as s', 's.id', '=', 'p.size_id')
            ->leftJoin('settlements AS d', 'd.product_id', '=', 'p.id')
            ->where('m.status', 'like', '%'.$status.'%')
//            ->where('m.situation',null)
//            ->where('p.provider_id',$id)
            ->where(DB::raw('DATE(m.date_shipment)'), '>=', $date1->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'), '<', $date2->toDateString())
            ->groupby('fecha')
            ->orderby('fecha', 'asc')
            ->get();

        $days=$date1->daysInMonth;

        $init=1;

        $days_list = array();
        $data_list = array();
        for ($x=1;$x<=$days;$x++) {
            array_push($days_list, $x);
        }

        foreach ($movements as $movement) {
            $date1 = Carbon::createFromFormat('Y-m-d', $movement->fecha);
            $date1->day;
//            array_push($days_list, $init);
            for ($i=$init;$i<=$days;$i++) {
                if ($date1->day==$i) {
                    array_push($data_list, $movement->cantidad);
//                    $prueba[$i]=$movement->cantidad;
                    break;
                } else {
                    array_push($data_list, 0);
//                    $prueba[$i]= 0;
                }
            }
            $init=$date1->day+1;
        }

        //falta crear vector con los dias y sus cantidades respectivamente. y devolverlas al proveedor y se muestren en el grafico

        return response()->json(['days_lists' => $days_list, 'data_lists' => $data_list]);
    }




    private function movementsGet($date1, $date2, $id)
    {
        $status = 'vendido';
        $movements=DB::table('auxproducts as p')
            ->select('m.date_shipment as fecha', 'p.cod as codigo', 'p.name as product', 'c.name as color',
                DB::raw('case when d.price then d.price else p.cost_provider + p.utility end as price'), 's.name as talla', 'm.status', 'm.discount',
                DB::raw('case when d.price then d.price-m.discount else p.cost_provider + p.utility -m.discount end as pricefinal'),
                DB::raw('case when d.price then 1 else 0 end as liquidacion'), 'p.cost_provider as cost')
            ->join('auxmovements as m', 'p.id', '=', 'm.product_id')
            ->join('colors as c', 'c.id', '=', 'p.color_id')
            ->join('sizes as s', 's.id', '=', 'p.size_id')
            ->leftJoin('settlements AS d', 'd.product_id', '=', 'p.id')
            ->where('m.status', 'like', '%'.$status.'%')
//            ->where('m.situation',null)
            ->where('p.provider_id', $id)
            ->where(DB::raw('DATE(m.date_shipment)'), '>=', $date1->toDateString())
            ->where(DB::raw('DATE(m.date_shipment)'), '<', $date2->toDateString())
            ->orderby('p.name', 'c.name', 's.name')
            ->get();

        return $movements;
    }
    
    public function getIdProvider(Request $request)
    {
        $user = $request->input('user')['sub'];
        $proveedor =  Provider::where('idUser', $user)->first();

        if ($proveedor != null) {
            $id=$proveedor->id;
            return response()->json(['provider_id'=>$id]);
        } else {
            return response()->json(['message'=>'Proveedor invalido']);
        }
    }

    public function movements_for_product($id)
    {
        try {
            
            // $product = Product::with(["color","size","settlement","movements" => function($query){
            //                 return $query->with("user");
            //             }])
            //             ->select("id","cod","name",DB::raw("cost_provider + utility as price_real"),"color_id","size_id")
            //             ->find($id);

            $movements = DB::table('auxproducts as p')
                ->select('m.created_at','m.date_request as pedido', 'm.cod_order', 'm.date_shipment as entrega', 'm.status', 'm.situation', 'm.discount')
                ->addSelect('p.cod as codigo', 'p.name as product', 'p.cost_provider', 'p.utility')
                ->addSelect('c.name as color', 's.name as talla')
                ->addSelect(DB::raw('p.cost_provider + p.utility  as price_real'))
                ->addSelect(DB::raw('case when dc.price then dc.price else p.cost_provider + p.utility end as price'))
                ->addSelect(DB::raw('case when dc.price then 1 else 0 end liquidation'))
                ->addSelect('u.first_name as seller')
                ->leftjoin('auxmovements as m', 'p.id', '=', 'm.product_id')
                ->join('colors as c', 'c.id', '=', 'p.color_id')
                ->join('sizes as s', 's.id', '=', 'p.size_id')
                ->leftJoin('settlements as dc', 'dc.product_id', '=', 'p.id')
                ->leftJoin('users as u', 'u.id', '=', 'm.user_id')
                ->where('p.id', $id)
                ->orderby('m.date_shipment', 'desc')
                ->get();

            foreach ($movements as $product) {
                $product->price_final = $product->price - $product->discount;
            }

            return response()->json(['movements' => $movements]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ricky esta jugando con los cables, no se puedo consultar los movimientos']);
        }
    }

    public function product_observe_detail($id)
    {
        $product = Product::find($id);
        if ($product == null) {
            return response()->json(['message'=>'el producto no existe'], 404);
        }
        return response()->json(['observe_detail'=>$product->observe_detail], 200);
    }

    /**
     *    Listado de motivos de cambio de estado,
     *    por estado de producto
     *
     *    @param  int  $id
     *    @return  Illuminate\Http\Response
     */
    public function reasonsList($id){
        $reasons = ProductStatusDetail::where("product_status_id", $id)->get();

        return $reasons;
    }

    /**
     *    Detalle de ultimo estado del producto
     *
     *    @param  int  $id
     *    @return  Illuminate\Http\Response
     */
    public function LastPruductStatusDetail($id)
    {
        $productStatusDtl = ProductDetailStatus::with("Product_status_detail")
                                                                ->where("product_id", $id)
                                                                ->orderBy("created_at","desc")
                                                                ->first();
        if ($productStatusDtl == null) {
            return response()->json(["message" => "No se pudo encontrar estado"]);
        }

        return $productStatusDtl->Product_status_detail;
    }

    public function setNewProductStatusDetail(Request $request){
        // return $request->all();
        $rules = [
            'status_id' =>'required|integer',
            'detail'=>'required|string'
        ];

        $validator = \Validator::make($request->all(),$rules);

        if($validator->fails()){
            echo "No valido";
            return response()->json(['message' => 'No se poseen todos los parametros para agregar un detalle de estado'],404);
        }

        $productStatusDtl= new ProductStatusDetail();
        $productStatusDtl->description=$request->input('detail');
        $productStatusDtl->product_status_id=$request->input('status_id');
        $productStatusDtl->save();
        // dd($productStatusDtl);

        return response()->json(['message' => 'Se agrego el nuevo detalle de estado correctamente'],200);

    }

    /**
     *    Cambio de estado de estado de un producto, guardar su registro
     *    y volver el estado a disponible al resolver ultimo estado.
     *
     *    @param  Illuminate\Http\Request  $request
     *    @param  int  $id
     *    @return  Illuminate\Http\Response
     */
    public function productStatusChange(Request $request, $id)
    {
        $product = Product::find($id);

        if ($product == null) { 
            return response()->json(["message" => "Parametros no validos"], 404);
        }

        $productStatusDtl = $this->ProductStatusDtl($request, $product);
         
        if ($productStatusDtl == null) {
            if($product->status==4){
                $status_id = $product->status;
            }else{
                return response()->json(["message" => "Parametros no validos"], 404);
            }
        } else {
            $status_id = $productStatusDtl->product_status_id;
        }        

        if ($status_id == 5) { // Exención para cambio de estado en venta 
            return $this->ProductTransition($product, $productStatusDtl);
        } elseif ($product->status != 1 && $status_id != $product->status) {
            return response()->json(["message" => "El producto no puede cambiar de estado ya que posee un estado distinto."], 401);
        }

        if ($product->status == 1) {
            event(new ProductStatusWasChanged($product, $productStatusDtl));
        } else {
            event(new ProductStatusWasChanged($product));
        }

        return response()->json(["message" => request("response", "Cambio de estado existoso."), "product" => $product]);
    }

    /**
     *    Resuelve el nuevo estado del producto o devuelve el ultimo.
     *
     *    @param  Illuminate\Http\Request  $request
     *    @param  Dashboard\Models\Experimental\Product  $product
     *    @return  Dashboard\Models\Experimental\ProductStatusDetail
     */
    private function productStatusDtl(Request $request, Product $product)
    {
        $productStatusDtl = null;

        if($request->has("reason_id")){
            $productStatusDtl = ProductStatusDetail::find(request("reason_id"));
        } else {
            $lastProductStatusDtl = ProductDetailStatus::with("Product_status_detail")
                                                        ->where("product_id", $product->id)
                                                        ->orderBy("created_at","desc")
                                                        ->first();

            if ($lastProductStatusDtl != null){
                $productStatusDtl = $lastProductStatusDtl->Product_status_detail;
            }
        }

        return $productStatusDtl;
    }

    /**
     *    Cambiar estado o generar nuevo estado de producto,
     *    en el caso de que sea una transición. y el producto este vendido.
     *
     *    @param  Dashboard\Models\Experimental\Product  $product
     *    @param  Dashboard\Models\Experimental\ProductStatusDetail  $productStatusDtl
     *    @return  Illuminate\Http\Response
     */
    private function ProductTransition(Product $product, ProductStatusDetail $productStatusDtl)
    {
        if ($product->status == 1) {
            return response()->json(["message" => "Solo se puede cambiar a trancision un producto en venta"], 401);
        } elseif ($product->status == 2) {
            event(new ProductStatusWasChanged($product, $productStatusDtl));
        } elseif ($product->status == 5) {
            $movement = $product->movements->first();
            $movement->situation='Transición';
            $movement->status="Retornado";
            $movement->save();

            event(new ProductStatusWasChanged($product));
        } else {
            return response()->json(["message" => "El producto no puede cambiar de estado ya que posee un estado distinto."], 401);
        }
        return response()->json(["message" => request("response", "Cambio de estado existoso."), "product" => $product]);
    }

}
