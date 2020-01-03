<?php

namespace App\Http\Controllers;

use App\Stock;
use Illuminate\Http\Request;

use PDF;

class StockCountController extends Controller
{


	public function index()
	{

	    $products = Stock::all();

        return view('inventory.count_sheet')->with(['products' => $products]);
	}



	public function generateDailyStockCountPDF(Request $request)
	{

		$data = $this->summation($request->sale_date);
		$new_data = array_values($data);


        $pdf = PDF::loadView('stock_management.daily_stock_count.daily_stock_count',
            compact('new_data'));

        return $pdf->stream('daily_stock_count.pdf');

	}


}
