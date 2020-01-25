<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Sale;
use App\SalesDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    //login form
    public function login()
    {
        return view('auth.login');
    }


    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $outOfStock = Stock::where('quantity', 0)->count();

        $belowMin = Stock::whereRaw('quantity <= min_quantinty and quantity > 0')->count();

        $totalSales = Sale::sum(DB::raw('quantity*selling_price'));

        $days = DB::table('sales')
            ->select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();

        if ($days->count() == 0) {
            $avgDailySales = 0;
        } else {
            $avgDailySales = $totalSales / $days->count();
        }

        $todaySales = DB::table('sales')
            ->whereRaw('date(created_at) = date(now())')
            ->sum(DB::raw('quantity*selling_price'));

        $totalDailySales = DB::table('sales')
            ->select(DB::raw('date(created_at) date, sum(quantity*selling_price) value'))
            ->groupBy(DB::raw('date(created_at)'))
            ->limit('30')
            ->get();

        $totalMonthlySales = DB::table('sales')
            ->select(DB::raw("DATE_FORMAT(created_at, '%b %y') month,sum(quantity*selling_price) amount"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->limit('12')
            ->get();

        // $salesByCategory = DB::table('sales')
        //     ->select(DB::raw("category,sum(amount) amount"))
        //     ->groupBy('category')
        //     ->get();


        return view('home', compact('outOfStock', 'belowMin','totalSales','todaySales','avgDailySales','totalDailySales','totalMonthlySales'));

    }
}
