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
        if($request->purchase_date =='NA'){
            $purchase_date = Carbon::now();
        }else{
            $purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        }


        $totalQty = $request->quantity * $request->quantity_per_unit;
        $salableUnitPrice = $request->unit_cost / $request->quantity_per_unit;

        $incoming = new IncomingStock;
        $incoming->product_id = $request->id;
        $incoming->quantity = $request->quantity;
        $incoming->supplier_id = $request->supplier;
        $incoming->unit_cost = $request->unit_cost;
        $incoming->created_by = Auth::User()->id;
        $incoming->created_at = $purchase_date;
        $incoming->updated_at = Carbon::now();
        $incoming->save();

        $stock = Stock::find($request->id);
        $stock->quantity = $stock->quantity + $totalQty;
        $stock->unit_cost = $salableUnitPrice;
        $stock->save();
        $request->flash();

        session()->flash("alert-success", "Purchase saved successfully!");
        return back();
    }

    public function history(){
        $now = Carbon::now();
        $purchases = IncomingStock::whereRaw('month(created_at) = month(now()) and year(created_at)=year(now())')->get();

        $total = 0;
        foreach ($purchases as $p) {
            $total = $total + ($p->quantity * $p->unit_cost);
        }

        $suppliers = Supplier::all();

        return view('purchases.history', compact("purchases","total",'suppliers'));
    }

    public function search(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        if($request->supplier == 0){
            $purchases = IncomingStock::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")->get();
        }else{
            $purchases = IncomingStock::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")
                        ->where('supplier_id',$request->supplier)->get();
        }
        
        $total = 0;
        foreach ($purchases as $p) {
            $total = $total + ($p->quantity * $p->unit_cost);
        }

        $suppliers = Supplier::all();
        $request->flash();

        return view('purchases.history', compact('purchases','total','suppliers'));
    }

    public function itemHistory(Request $request)
    {
         $history = IncomingStock::where('product_id', $request->prod_id)
        ->orderBy('id','desc')
        ->limit('7')
        ->get();

        $data = [];
        $prevPrice = [];
        foreach ($history as $h) {
            $data['date'] = date_format($h->created_at,'d M Y');
            $data['cost'] = $h->unit_cost;
            $data['supplier'] = $h->supplier->name;
            $prevPrice[]= $data;
        }
        return $history;
    }



}


