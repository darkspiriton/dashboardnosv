<?php

namespace Dashboard\Http\Controllers;

use Carbon\Carbon;
use DB;
use Dashboard\Http\Requests;
use Dashboard\Models\Experimental\Product;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    private $date;
    private $start;
    private $end;
    private $request;

    public function __construct(Carbon $date, Request $request)
    {
        $this->middleware('auth:all');
        $this->date = $date::now();
        $this->request = $request;
    }

    /**
     * Top 5 sales products
     */

    public function TopSales()
    {
        if (\Validator::make($this->request->all(), ['filter' => 'integer|between:1,4'])->fails()) {
            return response()->json(['message' => 'parametros no validos']);
        }
        
        $this->getDates();

        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id', '=', 3)->groupBy('name')->lists('name');

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

    public function TopLessSold()
    {
        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id', '=', 3)->groupBy('name')->lists('name');

        $top = DB::table('auxproducts')->select('name', DB::raw('count(`name`) as quantity'))
            ->whereIn('name', $products)
            ->where('status', '<>', 2)
            ->groupBy('name')
            ->orderBy('quantity', 'desc')
            ->take(5)->get();

        return response()->json(['TopLessSold' => $top], 200);
    }

    public function ProductStatusForSales()
    {
        if (\Validator::make($this->request->all(), ['filter' => 'integer|between:1,4'])->fails()) {
            return response()->json(['message' => 'parametros no validos']);
        }
        
        $this->getDates();

        $products = DB::table('auxproducts')->select(array('name'))->where('provider_id', '=', 3)->groupBy('name')->lists('name');

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

    private function getDates()
    {
        if (!$this->request->has("filter")) {
            $this->start = $this->date->copy()->firstOfMonth();
            $this->end = $this->date->copy()->lastOfMonth();
        } else {
            switch ($this->request->input("filter")) {
                case 1: // 1ra quincena
                    $this->start = $this->date->copy()->firstOfMonth();
                    $this->end   = $this->start->copy()->addWeeks(2);
                    break;
                case 2: // 2da quincena
                    $this->start = $this->date->copy()->firstOfMonth()->addWeeks(2);
                    $this->end   = $this->date->copy()->lastOfMonth();
                    break;
                case 3: // Semana actual
                    $this->start = $this->date->copy()->startOfWeek();
                    $this->end   = $this->date->copy()->endOfWeek();
                    break;
                default:    // Mes actual
                    $this->start = $this->date->copy()->firstOfMonth();
                    $this->end   = $this->date->copy()->lastOfMonth();
            }
        }
    }
}
