<?php

namespace App\Http\Controllers;

use App\CurrentStock;
use App\StockTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutGoingStockController extends Controller
{


    public function index()
    {
        return view('stock_management.out_going_stock.index');
    }

    public function showOutStock(Request $request)
    {

        if ($request->ajax()) {

            //return all
            $stock_tracking = StockTracking::whereBetween('updated_at', [date('Y-m-d', strtotime($request->date_from))
                , date('Y-m-d', strtotime($request->date))])
                ->where('movement', 'OUT')
                ->get();

            //return product object
            foreach ($stock_tracking as $tracking) {
                $tracking->currentStock->product;
                $tracking->user;
            }

            return json_decode($stock_tracking, true);

        }

    }


}
