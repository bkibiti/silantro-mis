<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Category;
use App\Sale;
use App\User;
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
        // $users = User::orderBy('name')->get();
        $users = User::permission('POS Users')->get();
        return view('sales.pos', compact('current_stock','categories','users'));
    }

    public function store(Request $request){

        $request->validate([
            'created_by' => 'required',
            'sale_order' => 'required',
        ]);

        $data = json_decode($request->sale_order,true);
        $receiptNo = $this->generateReceiptNo();
        $orderNo = $this->generateOrderNo();

        $order_details = [];
        $sale_date = '';

        if($request->sale_date =='NA'){
            $sale_date = Carbon::now();
        }else{
            $sale_date = date('Y-m-d', strtotime($request->sale_date));
        }

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
                'created_by' => $request->created_by,
                'created_at' => $sale_date,
                'updated_at' => Carbon::now(),
                'status' => '1',
            ];

            //deduct stock values from sales
            $stock = Stock::find($data[$i][0]);
            $stock->quantity = $stock->quantity - $data[$i][3];
            $stock->save();
        }

        DB::table('sales')->insert($order_details);
        
        $request->flash();

        session()->flash("alert-success", "Sale recorded successfully!");
        return back();

    }

    public function update(Request $request){

        $request->validate([
            'created_at' => 'required',
            'selling_price' => 'required',
            'quantity' => 'required',
            'buying_price' => 'required',
            'remarks' => 'required',
        ]);


        $Sale = Sale::findOrFail($request->id);
        $Sale->quantity = $request->quantity;
        $Sale->selling_price = $request->selling_price;
        $Sale->buying_price = $request->buying_price;
        $Sale->created_at = date('Y-m-d', strtotime($request->created_at));
        $Sale->updated_at = Carbon::now();
        $Sale->updated_by = Auth::user()->id;
        $Sale->remarks = $request->remarks;
        $Sale->save();
        

        session()->flash("alert-success", "Sale updated successfully!");
        return back();

    }

    public function history()
    {
        $sales = Sale::whereRaw('month(created_at) = month(now()) and year(created_at)=year(now())')->get();

        $total = 0;
        foreach ($sales as $s) {
            $total = $total + ($s->quantity * $s->selling_price);
        }

        return view('sales.history', compact('sales','total'));
    }

    public function historySearch(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        $sales = Sale::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")->get();

        $total = 0;
        foreach ($sales as $s) {
            $total = $total + ($s->quantity * $s->selling_price);
        }

        $request->flash();
        return view('sales.history', compact('sales','total'));
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
        $no = "OSN". $maxID;
        return $no;
    }
    public function generateReceiptNo(){
        $maxID = sale::latest()->value('id');
        if ($maxID ==''){
            $maxID = 1000000;
        }else{
            $maxID = $maxID + 1;
        }
        $no = "RSN". $maxID;
        return $no;
    }


 

}
