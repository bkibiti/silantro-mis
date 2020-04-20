<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Sale;
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

        $salesByUser = DB::select("SELECT users.name as user,sum(quantity*selling_price) as amount FROM 
                    sales join users on sales.created_by=users.id
                    where month(sales.created_at) = month(now())
                    group by users.name");
        $staffLoss = DB::select("SELECT users.name as user,sum(amount) as amount FROM 
                    staff_advances join users on staff_advances.user_id=users.id
                    where month(staff_advances.date) = month(now()) and type='loss'
                    group by users.name");

        return view('home', compact('salesByUser','staffLoss'));

    }

    public function dashboard()
    {

        $totalSales = Sale::sum(DB::raw('quantity*selling_price'));
        $totalExpenses = DB::table('expenses')->select(DB::raw('sum(Amount) Amount'))->get();
        $totalPurchases = DB::table('incoming_stock')->select(DB::raw('sum(unit_cost*quantity) Amount'))->get();
        $totalProft = DB::table('sales')->select(DB::raw('sum((selling_price-buying_price)*quantity) Amount'))->get();
        $days = DB::table('sales')
            ->select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();

        if ($days->count() == 0) {
            $avgDailySales = 0;
            $avgDailyExpenses = 0;
            $avgDailyPurchases = 0;
            $avgDailyProfit = 0;
        } else {
            $avgDailySales = $totalSales / $days->count();
            $avgDailyExpenses = $totalExpenses[0]->Amount / $days->count();
            $avgDailyPurchases = $totalPurchases[0]->Amount / $days->count();
            $avgDailyProfit = $totalProft[0]->Amount / $days->count();
        }

        $totalDailySales = DB::table('sales')
            ->select(DB::raw('date(created_at) date, sum(quantity*selling_price) value'))
            ->groupBy(DB::raw('date(created_at)'))
            ->orderBy('date','Desc')
            ->limit('30')
            ->get();
        // $totalMonthlySales = DB::table('sales')
        //     ->select(DB::raw("DATE_FORMAT(created_at, '%b %y') month,sum(quantity*selling_price) amount"))
        //     ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
        //     ->limit('12')
        //     ->get();

        $saleByDay = DB::table('sales')
            ->select(DB::raw('WEEKDAY(created_at) DayNumber,DAYNAME(created_at) DayName,sum(selling_price*quantity) Amount'))
            ->groupBy(DB::raw('WEEKDAY(created_at),DAYNAME(created_at)'))
            ->orderBy(DB::raw('DayNumber'))
            ->get();
        $saleByMonth = DB::table('sales')
            ->select(DB::raw('MONTHNAME(created_at) Month,sum(selling_price*quantity) Amount'))
            ->groupBy(DB::raw('MONTHNAME(created_at)'))
            ->get();
        
        $salesThisMonth = DB::table('sales')
            ->select(DB::raw('sum(selling_price*quantity) Amount'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->get();
        $salesLastMonth = DB::table('sales')
            ->select(DB::raw('sum(selling_price*quantity) Amount'))
            ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->get();
        $proftThisMonth = DB::table('sales')
            ->select(DB::raw('sum((selling_price-buying_price)*quantity) Amount'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->get();
        $profitLastMonth = DB::table('sales')
            ->select(DB::raw('sum((selling_price-buying_price)*quantity) Amount'))
            ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->get();
        $purchaseThisMonth = DB::table('incoming_stock')
            ->select(DB::raw('sum(unit_cost*quantity) Amount'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->get();
        $purchaseLastMonth = DB::table('incoming_stock')
            ->select(DB::raw('sum(unit_cost*quantity) Amount'))
            ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->get();
        $expensesThisMonth = DB::table('expenses')
            ->select(DB::raw('sum(Amount) Amount'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW())')
            ->get();
        $expensesLastMonth = DB::table('expenses')
            ->select(DB::raw('sum(Amount) Amount'))
            ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->get();

        return view('dashboard_advanced', compact('avgDailySales','totalDailySales','saleByDay','salesThisMonth','salesLastMonth','purchaseThisMonth','purchaseLastMonth',
                    'expensesThisMonth','expensesLastMonth','avgDailyExpenses','avgDailyPurchases','saleByMonth','proftThisMonth','profitLastMonth','avgDailyProfit'));

    }
}
