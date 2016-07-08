<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use DB;
use Dashboard\Http\Requests;
use Dashboard\Models\Experimental\Product;
use Dashboard\Models\Experimental\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    private $carbon;
    private $start;
    private $end;
    private $request;
    private $failed;
    private $message;
    private $provider_id;

    public function __construct(Request $request, Carbon $carbon)
    {
        $this->middleware('auth:all');
        $this->carbon = $carbon;
        $this->request = $request;
        $this->failed = false;
        $this->message = "";
    }

    /**
     * Top 5+ sales products
     * @return Collection
     */
    public function TopSales(Request $request)
    {
        $this->getIdProvider($request);
        if (\Validator::make($this->request->all(), ['filter' => 'integer|between:1,4'])->fails()) {
            return response()->json(['message' => 'parametros no validos']);
        }
        
        if ($this->getDates("Month")->failed) {
            return response()->json(["message" => $this->message], 401);
        }

        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id', '=', $this->provider_id)->groupBy('name')->lists('name');

        $top = DB::table('auxmovements as m')->select('p.name', DB::raw('count(`p`.`name`) as quantity'))
                        ->join('auxproducts as p', 'p.id', '=', 'm.product_id')
                        ->whereIn('p.name', $products)
                        ->where('m.status', '=', 'Vendido')
                        ->where('m.date_shipment', '>', $this->start)
                        ->where('m.date_shipment', '<', $this->end)
                        ->groupBy('p.name')
                        ->orderBy('quantity', 'desc')
                        ->take(5)->get();

        return response()->json(['TopSales' => $top], 200);
    }

    /**
     *  Top 5- stock in store
     *  @return Collection 
     */
    public function TopLessSold(Request $request)
    {
        $this->getIdProvider($request);
        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id', '=', $this->provider_id)->groupBy('name')->lists('name');

        $top = DB::table('auxproducts')->select('name', DB::raw('count(`name`) as quantity'))
            ->whereIn('name', $products)
            ->where('status', '<>', 2)
            ->groupBy('name')
            ->orderBy('quantity', 'desc')
            ->take(5)->get();

        return response()->json(['TopLessSold' => $top], 200);
    }

    /**
     *  Status sales product for provider 
     *  @return Collection, ChartData
     */
    public function ProductStatusForSales(Request $request)
    {
        $this->getIdProvider($request);
        if (\Validator::make($this->request->all(), ['filter' => 'integer|between:1,4'])->fails()) {
            return response()->json(['message' => 'parametros no validos']);
        }
        
        if ($this->getDates("Month")->failed) {
            return response()->json(["message" => $this->message], 401);
        }

        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id', '=', $this->provider_id)->groupBy('name')->lists('name');

        $top = DB::table('auxmovements as m')->select('p.name', DB::raw('count(`p`.`name`) as quantity'))
                        ->join('auxproducts as p', 'p.id', '=', 'm.product_id')
                        ->whereIn('p.name', $products)
                        ->where('m.status', '=', 'Vendido')
                        ->where('m.date_shipment', '>', $this->start)
                        ->where('m.date_shipment', '<', $this->end)
                        ->groupBy('p.name')
                        ->orderBy('quantity', 'desc')
                        ->get();

        $status = array();
        $status['days'] = Carbon::now()->day;
        $status['very_good'] = round($status['days'] / 1.5);
        $status['good'] = round($status['days'] / 3);
        $status['bad'] = 0;

        $chart = array();
        foreach ($top as $product) {
            $chart["data"][] = $product->quantity;
            $chart["labels"][] = $product->name;
            if ($product->quantity >= $status['very_good']) {
                $chart["colors"][] = "#4CAF50";
            } elseif ($product->quantity >= $status['good']) {
                $chart["colors"][] = "#FFEB3B";
            } else {
                $chart["colors"][] = "#f44336";
            }
        }

        return response()->json(['products' => $top, 'chart' => $chart], 200);
    }

    public function saleMonth(Request $request)
    {

        $this->getIdProvider($request);
        if ($this->getDates("Month")->failed) {
            return response()->json(["message" => $this->message], 401);
        }

        $status = 'vendido';
        $movements=DB::table('auxproducts as p')
            ->select('m.date_shipment as fecha', DB::raw('count(p.name) as cantidad'))
            ->join('auxmovements as m', 'p.id', '=', 'm.product_id')
            ->join('colors as c', 'c.id', '=', 'p.color_id')
            ->join('sizes as s', 's.id', '=', 'p.size_id')
            ->leftJoin('settlements AS d', 'd.product_id', '=', 'p.id')
            ->where('m.status', 'like', '%'.$status.'%')
//            ->where('m.situation',null)
           ->where('p.provider_id', $this->provider_id)
            ->where(DB::raw('DATE(m.date_shipment)'), '>=', $this->start)
            ->where(DB::raw('DATE(m.date_shipment)'), '<', $this->end)
            ->groupby('fecha')
            ->orderby('fecha', 'asc')
            ->get();

        $days = $this->start->daysInMonth;

        $init=1;

        $days_list = array();
        $data_list = array();
        for ($x=1;$x<=$days;$x++) {
            array_push($days_list, $x);
        }

        foreach ($movements as $movement) {
            $date1 = Carbon::parse($movement->fecha);
            $date1->day;
            for ($i=$init;$i<=$days;$i++) {
                if ($date1->day==$i) {
                    array_push($data_list, $movement->cantidad);
                    break;
                } else {
                    array_push($data_list, 0);
                }
            }
            $init=$date1->day+1;
        }

        //falta crear vector con los dias y sus cantidades respectivamente. y devolverlas al proveedor y se muestren en el grafico

        return response()->json(['days_lists' => $days_list, 'data_lists' => $data_list]);
    }

    public function movementsGet(Request $request)
    {
        $this->getIdProvider($request);
        if ($this->getDates()->failed) {
            return response()->json(["message" => $this->message], 401);
        }

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
            ->where('p.provider_id', $this->provider_id)
            ->where(DB::raw('DATE(m.date_shipment)'), '>=', $this->start)
            ->where(DB::raw('DATE(m.date_shipment)'), '<', $this->end)
            ->orderby('p.name', 'c.name', 's.name')
            ->get();

        return response()->json(['movements' => $movements], 200);
    }

    private function getDates($met = null)
    {
        $rules = [
            "year"  => "integer|between:2015,2030",
            "month" => "integer|between:1,12",
            "start" => "date",
            "end"   => "date"
        ];

        if (Validator::make($this->request->all(), $rules)->fails()) {
            $this->failed = true;
            $this->message = "parametros recividos no son validos";
            return $this;
        }

        if ($met) {
            $method = 'get'.$met;
            return $this->$method();
        }

        try {
            if ($this->request->has("year") && $this->request->has("month")) {
                $this->start = $this->carbon::create(
                    $this->request->input("year"),
                    $this->request->input("month"),
                     1, 0, 0, 0, 'America/Lima'
                );
                $this->end = $this->start->copy()->lastOfMonth()->addDay(1);
            } elseif ($this->request->has("start") && $this->request->has("end")) {
                $this->start = $this->carbon::parse($this->request->input('start'))
                    ->setTime(0, 0, 0);
                $this->end = $this->carbon::parse($this->request->input('end'))
                    ->setTime(0, 0, 0)
                    ->addDay(1);
            } elseif ($this->request->has("day")) {
                $this->start = $this->carbon::parse($this->request->input('day'))
                    ->setTime(0, 0, 0);
                $this->end = $this->start->copy()->addDay(1);
            } else {
                $this->start = $this->carbon::today();
                $this->end = $this->start->copy()->addDay(1);
            }

            return $this;
        } catch (\InvalidArgumentException $e) {
            $this->failed = true;
            $this->message = "fechas no validas";
            return $this;
        }
    }

    private function getMonth()
    {
        try {
            if ($this->request->has("year") && $this->request->has("month")) {
                $this->start = $this->carbon::create(
                    $this->request->input("year"),
                    $this->request->input("month"),
                     1, 0, 0, 0, 'America/Lima'
                );
                $this->end = $this->start->copy()->lastOfMonth()->addDay(1);
            } elseif ($this->request->has("start") && $this->request->has("end")) {
                $this->start = $this->carbon::parse($this->request->input('start'))
                    ->firstOfMonth();
                $this->end = $this->start->copy()->lastOfMonth();
            } elseif ($this->request->has("day")) {
                $this->start = $this->carbon::parse($this->request->input('day'))
                    ->firstOfMonth();
                $this->end = $this->start->copy()->lastOfMonth();
            } else {
                $this->start = $this->carbon::today()->firstOfMonth();
                $this->end = $this->start->copy()->lastOfMonth();
            }

            return $this;
        } catch (\InvalidArgumentException $e) {
            $this->failed = true;
            $this->message = "fechas no validas";
            return $this;
        }
    }

    public function getIdProvider($request)
    {
        if ($request->has("provider_id")){
            $this->provider_id = $request->input("provider_id");
        }else{
            $user_id = $request->input('user')['sub'];
            $proveedor = Provider::where('idUser', $user_id)->first();
            if ($proveedor != null) {
                $this->provider_id = $proveedor->id;
            }
        }
    }
}
