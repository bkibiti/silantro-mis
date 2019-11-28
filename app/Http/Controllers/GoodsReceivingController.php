<?php

namespace App\Http\Controllers;

use App\Product;
use App\Supplier;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use View;

class GoodsReceivingController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        return View::make('purchases.goods_receiving', (compact( 'suppliers','products')));
    }


    public function store(Request $request){
            dd($request->all());

    }



}


