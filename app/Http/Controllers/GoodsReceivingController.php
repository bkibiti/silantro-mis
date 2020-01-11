<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Supplier;
use App\IncomingStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoodsReceivingController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::all();
        $products = Stock::all();

        return View('purchases.goods_receiving', (compact( 'suppliers','products')));
    }


    public function store(Request $request){
    
        $totalQty = $request->quantity * $request->quantity_per_unit;
        $salableUnitPrice = $request->unit_cost / $request->quantity_per_unit;

        $incoming = new IncomingStock;
        $incoming->product_id = $request->id;
        $incoming->quantity = $request->quantity;
        $incoming->supplier_id = $request->supplier;
        $incoming->unit_cost = $request->unit_cost;
        $incoming->created_by = Auth::User()->id;
        $incoming->save();

        $stock = Stock::find($request->id);
        $stock->quantity = $stock->quantity + $totalQty;
        $stock->unit_cost = $salableUnitPrice;
        $stock->save();

        session()->flash("alert-success", "Purchase saved successfully!");
        return back();
    }

    public function history(){
        $now = Carbon::now();
        // $purchases = IncomingStock::whereMonth('created_at', $now->month)->get();
        $purchases = IncomingStock::all();

        $total = 0;
        foreach ($purchases as $p) {
            $total = $total + ($p->quantity * $p->unit_cost);
        }
        // dd($total);
        return view('purchases.history', compact("purchases","total"));
    }



}


