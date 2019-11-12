<?php

namespace App\Http\Controllers;

use App\CurrentStock;
use App\Customer;
use App\PriceCategory;
use App\PriceList;
use App\Sale;
use App\SalesCredit;
use App\SalesDetail;
use App\Setting;
use App\StockTracking;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use View;

class SaleController extends Controller
{

    public function cashSale()
    {
        $vat = Setting::where('id', 120)->value('value') / 100;//Get VAT %
        $back_date = Setting::where('id', 114)->value('value');
        $price_category = PriceCategory::orderBy('id', 'ASC')->get();
        $customers = Customer::orderBy('id', 'ASC')->get();
        $current_stock = CurrentStock::all();
        return View::make('sales.cash_sales.index')
            ->with(compact('customers'))
            ->with(compact('price_category'))
            ->with(compact('current_stock'))
            ->with(compact('back_date'))
            ->with(compact('vat'));
    }

    public function creditSale()
    {
        $vat = Setting::where('id', 120)->value('value') / 100;//Get VAT %
        $back_date = Setting::where('id', 114)->value('value');
        $price_category = PriceCategory::orderBy('id', 'ASC')->get();
        $customers = Customer::orderBy('id', 'ASC')->get();
        $current_stock = CurrentStock::all();
        return View::make('sales.credit_sales.index')
            ->with(compact('customers'))
            ->with(compact('price_category'))
            ->with(compact('back_date'))
            ->with(compact('current_stock'))
            ->with(compact('vat'));
    }

    public function getCreditsCustomers()
    {
        $customers = Customer::where('total_credit', '>', 0)->get();
        return View::make('sales.credit_sales.payment')
            ->with(compact('customers'));
    }

    public function getPaymentsHistory()
    {
        $payments = SalesCredit::join('sales', 'sales.id', '=', 'sales_credits.sale_id')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->get();
        return view('sales.payment_history.index', compact("payments"));
    }

    public function CreditSalePayment(Request $request)
    {
        $credit = new SalesCredit;
        $customer = Customer::find($request->customer_id);
        $credit->sale_id = $request->sale_id;
        $credit->paid_amount = $request->paid_amount;
        $credit->balance = $request->balance - $request->paid_amount;
        $credit->remark = $request->remark;
        $credit->created_by = Auth::User()->id;
        $credit->updated_by = Auth::User()->id;
        $customer->total_credit -= $request->paid_amount;
        $credit->save();
        $customer->save();
        session()->flash("alert-success", "Payment recorded successfully!");
        return back();

    }


    public function getCreditSale(Request $request)
    {

        $from = $request->date[0];
        $to = $request->date[1];

        if ($request->ajax()) {
            if ($request->id) {
                $sales = Sale::join('sales_credits', 'sales_credits.sale_id', '=', 'sales.id')
                    ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '>=', $from)
                    ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '<=', $to)
                    ->join('customers', 'customers.id', '=', 'sales.customer_id')
                    ->where('customer_id', $request->id)
                    ->groupBy('sale_id')
                    ->get();
            } else {
                $sales = Sale::where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '>=', $from)
                    ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '<=', $to)
                    ->join('sales_credits', 'sales_credits.sale_id', '=', 'sales.id')
                    ->join('customers', 'customers.id', '=', 'sales.customer_id')
                    ->groupBy('sale_id')
                    ->get();
            }

            foreach ($sales as $sale) {
                $outstanding = SalesCredit::where('sale_id', $sale->sale_id)->orderBy('id', 'desc')->first('balance');
                $discount = SalesDetail::where('sale_id', $sale->sale_id)->sum('discount');
                $amount = SalesDetail::where('sale_id', $sale->sale_id)->sum('amount');
                $sale->paid_amount = SalesCredit::where('sale_id', $sale->sale_id)->sum('paid_amount');
                $sale->balance = $outstanding->balance;
                $sale->total_amount = $amount - $discount;
            }
            $data = json_decode($sales, true);
            return $data;
        }
    }


    public function selectProducts(Request $request)
    {
        $output = [];
        $output[""] = "Select Product";
        $products = PriceList::where('price_category_id', $request->get('id'))
            ->join('inv_current_stock', 'inv_current_stock.id', '=', 'sales_prices.stock_id')
            ->join('inv_products', 'inv_products.id', '=', 'inv_current_stock.product_id')
            ->where('quantity', '>', 0)
            ->select('inv_products.id as id', 'name')
            ->groupBy('product_id')
            ->get();

        $count = count($products);
        if ($count <= 0) {
            $output[""] = "No Products Found";
        } else {
            $output[""] = "Select Product from the List";
        }

        foreach ($products as $product) {
            $latest = PriceList::where('price_category_id', $request->get('id'))
                ->join('inv_current_stock', 'inv_current_stock.id', '=', 'sales_prices.stock_id')
                ->join('inv_products', 'inv_products.id', '=', 'inv_current_stock.product_id')
                ->orderBy('stock_id', 'desc')
                ->where('product_id', $product->id)
                ->first('price');
            $quantity = CurrentStock::where('product_id', $product->id)->sum('quantity');
            $output["$product->name#@$latest->price#@$product->id#@$quantity"] = $product->name;
        }
        return $output;
    }

    public function storeCashSale(Request $request)
    {
//store
        $this->store($request);
        return redirect()->route('getCashReceipt');
    }

    public function store(Request $request)
    {

        date_default_timezone_set('Africa/Nairobi');
//some attributes declaration
        $vat = Setting::where('id', 120)->value('value') / 100;//Get VAT %
        $receipt_number = strtoupper(substr(md5(microtime()), rand(0, 26), 8));
        $cart = json_decode($request->cart, true);
        $discount = $request->discount_amount;
        $total = 0;
        if ($request->sale_date) {
            $date = $request->sale_date;
        } else {
            $date = date('Y-m-d,H:i:s');
        }
//Avoid submission of a null Cart
        if (!$cart) {
            session()->flash("alert-danger", "You can not save an empty Cart!");
        } else {
//calculating the Total Amount
            foreach ($cart as $bought) {
                $total += $bought['amount'];
            }

//Saving Sale Summary and Get its ID
            $sale = DB::table('sales')->insertGetId(array(
                'receipt_number' => $receipt_number,
                'customer_id' => $request->customer_id,
                'price_category_id' => $request->price_category_id,
                'date' => $date,
                'created_by' => Auth::User()->id
            ));

//Saving Sale Details
            foreach ($cart as $bought) {
                if ($bought['quantity'] > 0) {
                    $unit_discount = (($bought['amount'] / $total) * $discount) / $bought['quantity'];
                    $unit_price = $bought['price'];
                    $stocks = CurrentStock::where('product_id', $bought['product_id'])
                        ->where('quantity', '>', 0)
                        ->get();

                    foreach ($stocks as $stock) {

                        /*stock tracking */
                        if ($request->credit == 'Yes') {
                            /*save in stock tracking as OUT*/
                            if ($bought['quantity'] != 0) {
                                $stock_tracking = new StockTracking;
                                $stock_tracking->stock_id = $stock->id;
                                $stock_tracking->product_id = $bought['product_id'];
                                $stock_tracking->quantity = $bought['quantity'];
                                $stock_tracking->store_id = 1;
                                $stock_tracking->updated_by = Auth::user()->id;
                                $stock_tracking->out_mode = 'Credit Sales';
                                $stock_tracking->updated_at = date('Y-m-d');
                                $stock_tracking->movement = 'OUT';
                                $stock_tracking->save();
                            }
                        } else {
                            /*save in stock tracking as OUT*/
                            if ($bought['quantity'] != 0) {
                                $stock_tracking = new StockTracking;
                                $stock_tracking->stock_id = $stock->id;
                                $stock_tracking->product_id = $bought['product_id'];
                                $stock_tracking->quantity = $bought['quantity'];
                                $stock_tracking->store_id = 1;
                                $stock_tracking->updated_by = Auth::user()->id;
                                $stock_tracking->out_mode = 'Cash Sales';
                                $stock_tracking->updated_at = date('Y-m-d');
                                $stock_tracking->movement = 'OUT';
                                $stock_tracking->save();
                            }
                        }/*end stock tracking*/

                        if ($bought['quantity'] <= $stock->quantity) {
                            $qty = $bought['quantity'];
                            $price = $unit_price * $qty;
                            $sale_discount = $unit_discount * $qty;
                            $stock->quantity -= $qty;
                            $stock->created_by = Auth::User()->id;
                            $bought['quantity'] -= $qty;
                        } else {
                            $qty = $stock->quantity;
                            $sale_discount = $unit_discount * $qty;
                            $price = $unit_price * $qty;
                            $stock->quantity = 0;
                            $stock->created_by = Auth::User()->id;
                            $bought['quantity'] -= $qty;
                        }
                        if ($qty > 0) {
                            $details = new SalesDetail;
                            $details->sale_id = $sale;
                            $details->stock_id = $stock->id;
                            $details->quantity = $qty;
                            $details->price = $price;
                            $details->vat = $details->price * $vat;
                            $details->amount = $details->price + $details->vat;
                            $details->discount = $sale_discount;
                            $details->save();
                            $stock->save();
                        }
                    }
                }
            }
//credit Sale
            if ($request->credit == 'Yes') {

                $credit = new SalesCredit;
                $customer = Customer::find($request->customer_id);
                $credit->sale_id = $sale;
                $credit->paid_amount = $request->paid_amount;
                $credit->balance = $total - $discount - $request->paid_amount;
                $credit->grace_period = $request->grace_period;
                $credit->remark = $request->remark;
                $credit->created_by = Auth::User()->id;
                $credit->updated_by = Auth::User()->id;
                $customer->total_credit += $credit->balance;
                $credit->save();
                $customer->save();
            }
            session()->flash("alert-success", "Sale recorded successfully!");
        }

    }

    public function storeCreditSale(Request $request)
    {
//Get the ID of customer from JSON Object
        $customer = json_decode($request->customer_id, true);
        if ($customer) {
            $request->customer_id = $customer['id'];
            $this->store($request);
            return redirect()->route('getCreditReceipt');
        } else {
            session()->flash("alert-danger", "Customer Name is Required");
            return back();
        }

    }

    public function getSalesHistory(Request $request)
    {
        $from = $request->range[0];
        $to = $request->range[1];
        $user = Auth::user()->id;
        $sales = Sale::where('created_by', $user)
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '>=', $from)
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '<=', $to)
            ->get();
        foreach ($sales as $sale) {
            $sale->cost;
            $sale->details;
        }
        $data = json_decode($sales, true);
        return $data;
    }

    public function SalesHistory()
    {

        $vat = Setting::where('id', 120)->value('value') / 100;//Get VAT %
        return View::make('sales.sales_history.index')
            ->with(compact('vat'));
    }


    public function creditsTracking()
    {
        $customers = Customer::where('total_credit', '>', 0)->get();
        return View::make('sales.credit_tracking.index')
            ->with(compact('customers'));
    }


    public function getCashReceipt()
    {

        $pharmacy['name'] = Setting::where('id', 100)->value('value');
        $pharmacy['logo'] = Setting::where('id', 105)->value('value');
        $pharmacy['address'] = Setting::where('id', 106)->value('value');
        $pharmacy['tin_number'] = Setting::where('id', 102)->value('value');
        $id = SalesDetail::orderBy('id', 'desc')->value('sale_id');
        $sale_detail = SalesDetail::where('sale_id', $id)->get();

        $sales = array();
        $grouped_sales = array();
        $sn = 0;
        foreach ($sale_detail as $item) {
            $amount = $item->amount - $item->discount;
            $vat_percent = $item->vat / $item->price;
            $sub_total = ($amount / (1 + $vat_percent));
            $vat = $amount - $sub_total;
            $sn++;
            array_push($sales, array(
                'receipt_number' => $item->sale['receipt_number'],
                'name' => $item->currentStock['product']['name'],
                'sn' => $sn,
                'quantity' => $item->quantity,
                'vat' => $vat,
                'discount' => $item->discount,
                'price' => $item->price,
                'amount' => $amount,
                'sub_total' => $sub_total,
                'grand_total' => ($item->sale['cost']['amount']) - ($item->sale['cost']['discount']),
                'total_vat' => ($item->sale['cost']['vat']),
                'sold_by' => $item->sale['user']['name'],
                'customer' => $item->sale['customer']['name'],
                'created_at' => date('Y-m-d', strtotime($item->sale['date']))
            ));
        }


        foreach ($sales as $val) {
            if (array_key_exists('receipt_number', $val)) {
                $grouped_sales[$val['receipt_number']][] = $val;
            }
        }

        $data = $grouped_sales;
        $pdf = PDF::loadView('sales.cash_sales.receipt',
            compact('data', 'pharmacy'));

        return $pdf->download('Receipt.pdf');
        return back();

//return $grouped_sales;
    }

    public function getCreditReceipt()
    {
        $pharmacy['name'] = Setting::where('id', 100)->value('value');
        $pharmacy['logo'] = Setting::where('id', 105)->value('value');
        $pharmacy['address'] = Setting::where('id', 106)->value('value');
        $pharmacy['tin_number'] = Setting::where('id', 102)->value('value');
        $id = SalesDetail::orderBy('id', 'desc')->value('sale_id');
        $sale_detail = SalesDetail::join('sales_credits', 'sales_credits.sale_id', '=', 'sales_details.sale_id')
            ->where('sales_credits.sale_id', $id)->get();
        $sales = array();
        $grouped_sales = array();
        $sn = 0;
        foreach ($sale_detail as $item) {

            $amount = $item->amount - $item->discount;
            $vat_percent = $item->vat / $item->price;
            $sub_total = ($amount / (1 + $vat_percent));
            $vat = $amount - $sub_total;
            $sn++;
            array_push($sales, array(
                'receipt_number' => $item->sale['receipt_number'],
                'name' => $item->currentStock['product']['name'],
                'paid' => $item->paid_amount,
                'balance' => $item->balance,
                'sn' => $sn,
                'quantity' => $item->quantity,
                'vat' => $vat,
                'discount' => $item->discount,
                'price' => $item->price,
                'amount' => $amount,
                'sub_total' => $sub_total,
                'total_discount' => $item->sale['cost']['discount'],
                'grand_total' => ($item->sale['cost']['amount']) - ($item->sale['cost']['discount']),
                'total_vat' => ($item->sale['cost']['vat']),
                'sold_by' => $item->sale['user']['name'],
                'customer' => $item->sale['customer']['name'],
                'created_at' => date('Y-m-d', strtotime($item->sale['date']))
            ));
        }


        foreach ($sales as $val) {
            if (array_key_exists('receipt_number', $val)) {
                $grouped_sales[$val['receipt_number']][] = $val;
            }
        }
        $data = $grouped_sales;
        $pdf = PDF::loadView('sales.credit_sales.receipt',
            compact('data', 'pharmacy'));
        return $pdf->download('Recept.pdf');
    }

}
