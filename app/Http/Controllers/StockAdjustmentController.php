<?php

namespace App\Http\Controllers;

use App\AdjustmentReason;
use App\Stock;
use App\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{

    public function index()
    {
        $stock_adjustments = StockAdjustment::orderBy('id', 'ASC')->get();
        $products = Product::all();
        $adjustment_reason = AdjustmentReason::all('reason');

        return view('stock_management.stock_adjustment.index')->with([
            'adjustments' => $stock_adjustments,
            'products' => $products,
            'reasons' => $adjustment_reason
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());

        $product = Stock::find($request->id);
        $product->sale_price_1 = $request->sale_price_1;
        $product->sale_price_2 = $request->sale_price_2;
        $product->sale_price_3 = $request->sale_price_3;
        $product->save();
     
        session()->flash("alert-success", "Selling Price Updated successfully!");
        return back();

    }

    public function update(Request $request)
    {

    }

    public function destroy(Request $request)
    {

    }

   
}
