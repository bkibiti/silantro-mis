<?php

namespace App\Http\Controllers;

use App\Stock;
use App\PriceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrentStockController extends Controller
{

    public function index()
    {
        $Products = Stock::all();
        $PriceCategory = PriceCategory::all();

        return view('inventory.index', compact("Products","PriceCategory"));
    }

    public function update(Request $request){
       
        $product = Stock::find($request->id);
        $product->sale_price_1 = $request->sale_price_1;
        $product->sale_price_2 = $request->sale_price_2;
        $product->sale_price_3 = $request->sale_price_3;
        $product->save();
     
        session()->flash("alert-success", "Selling Price Updated successfully!");
        return back();

    }

 

}
