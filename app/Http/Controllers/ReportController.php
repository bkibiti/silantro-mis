<?php

namespace App\Http\Controllers;
use App\Sale;

use DB;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    protected function getReport(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        switch ($request->report) {
            case 1:
                $data = $this->TotalDailySales($from, $to);
                $request->flash();
                return view('reports.total_daily_sales', compact('data'));
                break;
            case 2:
                $data = $this->TotalMonthlySale($from, $to);
                $request->flash();
                return view('reports.total_daily_monthly', compact('data'));
                break;
            case 3:
                $data = $this->fastMovingItems();
                $request->flash();
                return view('reports.fast_moving_items', compact('data'));
                break;
            case 4:
                $data = $this->grossProfit($from, $to);
                $total = 0;
                foreach ($data as $d) {
                    $total = $total + $d->profit;
                }
                $request->flash();
                return view('reports.gross_profit', compact('data','total'));
                break;
            case 5:
                $data = $this->stockValue();
                $total = DB::table('stock')->selectRaw('sum(quantity*unit_cost) total_purchase_value,sum(quantity*sale_price_1) total_sale_value')->get();
                $request->flash();
                return view('reports.stock_value', compact('data','total'));
                break;
            default:

        }
    }

    private function TotalDailySales($from, $to)
    {
        $totalDailySales = DB::table('sales')
            ->select(DB::raw('date(created_at) date, sum(quantity*selling_price) amount'))
            ->whereRaw("date(created_at) between '". $from . "' and '". $to ."'")
            ->groupBy(DB::raw('date(created_at)'))
            ->orderBy('date','Desc')
            ->get();

        return $totalDailySales;
    }

    private function TotalMonthlySale($from, $to)
    {
        $totalMonthlySales = DB::table('sales')
            ->select(DB::raw("DATE_FORMAT(created_at, '%b %Y') month,sum(quantity*selling_price) amount"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->limit('12')
            ->get();

        return $totalMonthlySales;
    }

    private function fastMovingItems()
    {
        $soldqty = DB::table('sales')
            ->selectRaw('stock.name,sum(sales.quantity) qty')
            ->join('stock', 'stock.id', '=', 'sales.stock_id')
            ->groupBy('stock.name')
            ->orderBy('qty','desc')
            ->get();
        return $soldqty;
    }

    private function stockValue()
    {
        $values = DB::table('stock')
            ->selectRaw('name,quantity,(quantity*unit_cost) purchase_value,(quantity*sale_price_1) sale_value')
            ->where('quantity','>',0)
            ->get();
        return $values;
    }
  

    private function grossProfit($from, $to)
    {
        $profit = DB::table('sales')
            ->selectRaw('date(created_at) date,sum((selling_price-buying_price)*quantity) profit')
            ->whereRaw("date(created_at) between '". $from . "' and '". $to ."'")
            ->groupBy(DB::raw('date(created_at)'))
            ->orderBy('date','Desc')
            ->get();

        return $profit;
    }



}
