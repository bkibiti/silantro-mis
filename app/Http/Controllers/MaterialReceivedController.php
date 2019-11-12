<?php

namespace App\Http\Controllers;

use App\GoodsReceiving;
use App\Product;
use App\Supplier;
use DB;
use Illuminate\Http\Request;
use View;

class MaterialReceivedController extends Controller
{

    public function index(Request $request)
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        return View::make('purchases.material_received.index', (compact('suppliers', 'products')));

    }

    public function getMaterialsReceived(Request $request)
    {
        $from = $request->date[0];
        $to = $request->date[1];
        $total_bp = 0;
        $total_sp = 0;
        $total_pf = 0;
        $data = array();
        $material_received = GoodsReceiving::where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '>=', $from)
            ->where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '<=', $to)
            ->get();

        if ($request->supplier_id) {
            $material_received = GoodsReceiving::where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '>=', $from)
                ->where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '<=', $to)
                ->where('supplier_id', $request->supplier_id)
                ->get();
        }
        if ($request->product_id) {
            $material_received = GoodsReceiving::where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '>=', $from)
                ->where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '<=', $to)
                ->where('product_id', $request->product_id)
                ->get();
        }
        if (($request->product_id) && ($request->supplier_id)) {
            $material_received = GoodsReceiving::where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '>=', $from)
                ->where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '<=', $to)
                ->where('product_id', $request->product_id)
                ->where('supplier_id', $request->supplier_id)
                ->get();
        }

        foreach ($material_received as $material){
            $total_bp = $total_bp + $material->total_cost;
            $total_sp = $total_sp + $material->sell_price;
            $total_pf = $total_pf + $material->item_profit;
        }

        foreach ($material_received as $value) {
            $value->product;
            $value->supplier;
        }

        array_push($data, array(
           $material_received,$total_bp,$total_sp,$total_pf
        ));

//        $data = json_decode($material_received, true);
        return $data;
    }
}
