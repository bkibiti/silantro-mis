<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\StaffAdvance;
use App\User;

class StaffAdvanceController extends Controller
{
    public function index()
    {
        $StaffAdvance = StaffAdvance::whereRaw('month(date) = month(now())')->get();
        $users = User::where('status',1)->orderBy('name')->get();

        return view('expense.staff_advance.index', compact('StaffAdvance','users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'amount' => 'required',
            'remarks'=> 'max:200',
        ]);

        $adv_date = date('Y-m-d', strtotime($request->date));

        $advance = new StaffAdvance;
        $advance->amount = $request->amount;
        $advance->date = $adv_date;
        $advance->type = $request->type;
        $advance->user_id =$request->user;
        $advance->remarks =$request->remarks;
        $advance->save();

        session()->flash("alert-success", "Expense added successfully!");
        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'amount' => 'required',
            'remarks'=> 'max:200',
        ]);

        $adv_date = date('Y-m-d', strtotime($request->date));

        $advance = StaffAdvance::findOrFail($request->id);
        $advance->amount = $request->amount;
        $advance->date = $adv_date;
        $advance->type = $request->type;
        $advance->user_id =$request->user;
        $advance->remarks =$request->remarks;
        $advance->save();

        session()->flash("alert-success", "Expense updated successfully!");
        return back();
    }

}
