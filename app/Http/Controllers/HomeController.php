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
        $days = DB::table('sales')->select(DB::raw('date(created_at)'))->distinct()->get();

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

        $stockValue = Stock::sum(DB::raw('quantity*sale_price_1'));

        $valueByCategory = DB::select("SELECT p.name,sum(s.quantity * s.sale_price_1) amount FROM stock s
                            JOIN product_categories p on s.category_id=p.id GROUP BY p.name HAVING amount > 0");         
       
        $totalDailySales = DB::table('sales')
            ->select(DB::raw('date(created_at) date, sum(quantity*selling_price) value'))
            ->groupBy(DB::raw('date(created_at)'))
            ->orderBy('date','Desc')
            ->limit('30')
            ->get();

        $monthlyTrends = DB::select("select Q1.month_no,Q1.month,Q1.amount as sales,Q2.amount as expenses from 
                    (SELECT DATE_FORMAT(created_at,'%Y-%m') month_no, DATE_FORMAT(created_at,'%b-%y') month, sum(quantity*selling_price) as amount 
                    FROM sales GROUP BY month_no,month ORDER BY month_no desc limit 12) as Q1
                    left join 
                    (SELECT DATE_FORMAT(created_at,'%Y-%m') month_no, DATE_FORMAT(created_at,'%b-%y') month, sum(amount) as amount 
                    FROM expenses GROUP BY month_no,month ORDER BY month_no desc limit 12) as Q2
                    on Q1.month_no = Q2.month_no 
                    order by Q1.month_no
                    ");
       
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

        $thisMonth = [];
        $thisMonth['purchase'] = $purchaseThisMonth[0]->Amount;
        $thisMonth['sales'] = $salesThisMonth[0]->Amount;
        $thisMonth['expense'] = $expensesThisMonth[0]->Amount;
        $thisMonth['profit'] = $proftThisMonth[0]->Amount;

        $LastMonth = [];
        $lastMonth['purchase'] = $purchaseLastMonth[0]->Amount;
        $lastMonth['sales'] = $salesLastMonth[0]->Amount;
        $lastMonth['expense'] = $expensesLastMonth[0]->Amount;
        $lastMonth['profit'] = $profitLastMonth[0]->Amount;
       
        $dailyAverage = [];
        $dailyAverage['purchase'] = $avgDailyPurchases;
        $dailyAverage['sales'] = $avgDailySales;
        $dailyAverage['expense'] = $avgDailyExpenses;
        $dailyAverage['profit'] = $avgDailyProfit;


        return view('dashboard_advanced', compact('dailyAverage','thisMonth','lastMonth','totalDailySales','saleByDay','saleByMonth',
                    'monthlyTrends','stockValue','valueByCategory'));

    }
}
