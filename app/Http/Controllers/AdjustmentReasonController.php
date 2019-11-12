<?php

namespace App\Http\Controllers;

use App\AdjustmentReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdjustmentReasonController extends Controller
{
    public function index()
    {
        $adjustment = AdjustmentReason::orderBy('id', 'ASC')->get();
        return view('masters.adjustment_reason.index')->with('adjustment', $adjustment);
    }

    public function store(Request $request)
    {
        $adjustment = new AdjustmentReason;
        $adjustment->reason = $request->reason;
        $adjustment->save();
        session()->flash("alert-success", "Adjustment reason added successfully!");
        return back();
    }

    public function update(Request $request)
    {
        $adjustment = AdjustmentReason::find($request->adjustment_id);
        $adjustment->reason = $request->name;
        $adjustment->save();
        session()->flash("alert-success", "Adjustment reason updated successfully!");
        return back();

    }

   public function destroy(Request $request)
    {
         try {
            AdjustmentReason::destroy($request->id);
            session()->flash("alert-danger", "Adjustment Reason Deleted successfully!");
            return back();
        } catch (\Exception $exception) {
            session()->flash("alert-danger", "Adjustment Reason in use!");
            return back();
        }
        
    }

}
