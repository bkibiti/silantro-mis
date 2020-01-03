<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Category;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;


class SaleController extends Controller
{

    public function cashSale()
    {
        
        $current_stock = Stock::where('quantity','>',0)->get();
        $categories = Category::orderBy('name')->get();
        
        return view('sales.pos', compact('current_stock','categories'));


    }

 

 

}
