<?php

namespace App\Http\Controllers;

use App\Stock;
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

        $belowMin = Stock::whereRaw('quantity <= min_quantinty')->count();

        // $totalSales = SalesDetail::sum('amount');

        // $days = DB::table('sale_details')
        //     ->select(DB::raw('date(sold_at)'))
        //     ->distinct()
        //     ->get();

        // if ($days->count() == 0) {
        //     $avgDailySales = 0;
        // } else {
        //     $avgDailySales = $totalSales / $days->count();
        // }

        // $todaySales = DB::table('sale_details')
        //     ->whereRaw('date(sold_at) = date(now())')
        //     ->sum('amount');

        // $totalDailySales = DB::table('sale_details')
        //     ->select(DB::raw('date(sold_at) date, sum(amount) value'))
        //     ->groupBy(DB::raw('date(sold_at)'))
        //     ->limit('60')
        //     ->get();

        // $totalMonthlySales = DB::table('sale_details')
        //     ->select(DB::raw("DATE_FORMAT(sold_at, '%b %y') month,sum(amount) amount"))
        //     ->groupBy(DB::raw("DATE_FORMAT(sold_at, '%Y%m')"))
        //     ->get();

        // $salesByCategory = DB::table('sale_details')
        //     ->select(DB::raw("category,sum(amount) amount"))
        //     ->groupBy('category')
        //     ->get();


        return view('home', compact('outOfStock', 'belowMin'));

    }
}
