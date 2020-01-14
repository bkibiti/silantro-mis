<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Sale;
use App\DailySale;


class DailySaleController extends Controller
{
    
    public function index()
    {
        $DailySale = DailySale::get();
        
        return view('sales.daily_report.index', compact('DailySale'));
    }


    public function generate(Request $request){

        $request->validate([
            'other_expenses' => 'required|numeric',
            'other_income' => 'required|numeric',
            'report_date' => 'required',
            'submission_remarks' => 'required|min:3',
        ]);
    
        $report_date = date('Y-m-d', strtotime($request->report_date));

        $totalExpenses = DB::table('expenses')
                ->where('created_at', $report_date)
                ->sum('amount');

        $totalSales = DB::table('sales')
                ->where('created_at', $report_date)
                ->sum(DB::raw('quantity*selling_price'));
        $cash = $totalSales + $request->other_income - $totalExpenses - $request->other_expenses;

        $exist = DailySale::where('report_date',$report_date)->get();
        
        if ($exist->count() == 0){
            DailySale::Create(
                ['report_date' => $report_date,'status' => 'Pending',
                'sales' => $totalSales, 'expenses' => $totalExpenses, 'other_income'=> $request->other_income,
                'other_expenses' =>$request->other_expenses,'submission_remarks' => $request->submission_remarks,
                'cash_on_hand' => $cash ,'submitted_by' =>Auth::user()->id,'submitted_at' => Carbon::now(), 
                'status' => 'Pending'] );
        }else{
            session()->flash("alert-danger", "The report for this date is already generated!");
            return back();
        }
       

        session()->flash("alert-success", "Report created successfully!");
        return back();

    }
}
