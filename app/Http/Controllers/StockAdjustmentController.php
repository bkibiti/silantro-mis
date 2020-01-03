<?php

namespace App\Http\Controllers;

use App\Stock;
use App\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{

    public function store(Request $request)
    {
        // dd($request->all());

        $original_qty = $request->quantity;
        $adjusted_qty = $request->qnty;

        if ($request->type == 'Positive'){
            $remain_qty = $original_qty + $adjusted_qty;
        }
        if ($request->type == 'Negative'){
            $remain_qty = $original_qty - $adjusted_qty;
        }

        $product = Stock::find($request->id);
        $product->quantity = $remain_qty;
        $product->save();

        //insert into adjustment table
        $adjust = new StockAdjustment;
        $adjust->stock_id = $request->id;
        $adjust->store_id = $product->store_id;
        $adjust->original_qty = $original_qty;
        $adjust->adjusted_qty = $adjusted_qty;
        $adjust->remain_qty = $remain_qty;
        $adjust->adjustment_type = $request->type;
        $adjust->reason = $request->reason;
        $adjust->created_by = Auth::user()->id;
        $adjust->save();
     
        session()->flash("alert-success", "Stock Adjusted Successfully!");
        return back();
    }

    public function history()
    {
        $adjustments = StockAdjustment::all();

        return view('inventory.stock_adjustment_history', compact("adjustments"));

    }

    public function destroy(Request $request)
    {

    }

   
}
