<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Category;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SaleController extends Controller
{

    public function index()
    {
        $current_stock = Stock::where('quantity','>',0)->get();
        $categories = Category::orderBy('name')->get();
        
        return view('sales.pos', compact('current_stock','categories'));
    }

    public function store(Request $request){
        $data = json_decode($request->sale_order,true);
        $receiptNo = $this->generateReceiptNo();
        $orderNo = $this->generateOrderNo();

        $order_details = [];

        for($i= 0; $i < count($data); $i++){
            $order_details[] = [
                'order_number' => $orderNo,
                'table_number' => $request->table_number,
                'customer' => $request->customer,
                'receipt_number' => $receiptNo,
                'stock_id' => $data[$i][0],
                'quantity' => $data[$i][3],
                'buying_price' => $data[$i][6],
                'selling_price' => str_replace(',','',$data[$i][4]) ,
                'created_by' => Auth::User()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'status' => '0',
            ];
        }

        DB::table('sales')->insert($order_details);

        session()->flash("alert-success", "Order placed successfully!");
        return back();

    }

    public function pendingOrders(){
        $orders = sale::where('status',0)->get();
        $order_nos = DB::table('sales')->distinct()->select('order_number')->get();

        return view('sales.pending_order', compact('orders','order_nos'));

    }
    
    public function generateOrderNo(){
        $maxID = sale::latest()->value('id');
        if ($maxID ==''){
            $maxID = 1000000;
        }else{
            $maxID = $maxID + 1;
        }
        $no = "SON-". $maxID;
        return $no;
    }
    public function generateReceiptNo(){
        $maxID = sale::latest()->value('id');
        if ($maxID ==''){
            $maxID = 1000000;
        }else{
            $maxID = $maxID + 1;
        }
        $no = "SRN-". $maxID;
        return $no;
    }


 

}
