<?php

namespace App\Http\Controllers;

use App\Stock;
use App\PriceCategory;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrentStockController extends Controller
{

    public function index()
    {
        $Products = Stock::all();
        $PriceCategory = PriceCategory::all();
        $categories = Category::orderBy('id', 'DESC')->get();

        return view('inventory.index', compact("Products","PriceCategory","categories"));
    }

    public function update(Request $request){
       
        $product = Stock::find($request->id);
        $product->unit_cost = $request->unit_cost;
        $product->sale_price_1 = $request->sale_price_1;
        $product->sale_price_2 = $request->sale_price_2;
        $product->sale_price_3 = $request->sale_price_3;
        $product->save();
     
        session()->flash("alert-success", "Price Updated successfully!");
        return back();

    }

    public function filter(Request $request){
     
        if ($request->status == '0'){
            $Products = Stock::where('category_id', 'like', '%' . $request->category_id. '%')->get();;
        }
        if ($request->status == '1'){
            $Products = Stock::where('quantity',0)->where('category_id', 'like', '%' . $request->category_id . '%')->get();
        }
        if ($request->status == '2'){
            $Products = Stock::whereRaw('quantity <= min_quantinty and quantity > 0')
                                ->where('category_id', 'like', '%' . $request->category_id . '%')
                                ->get();
        }

        $PriceCategory = PriceCategory::all();
        $categories = Category::orderBy('id', 'DESC')->get();
        $request->flash();

        return view('inventory.index', compact("Products","PriceCategory","categories"));
    }


    
   
 

}
