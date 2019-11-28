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
       

    }

 

}
