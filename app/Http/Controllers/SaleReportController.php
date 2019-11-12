<?php

namespace App\Http\Controllers;

use App\Customer;
use App\PriceCategory;
use App\PriceList;
use App\Sale;
use App\SalesCredit;
use App\SalesDetail;
use App\SalesReturn;
use App\Setting;
use DB;
use Illuminate\Http\Request;
use PDF;

class SaleReportController extends Controller
{
    public function index()
    {
        $price_category = PriceCategory::all();
        $customers = Customer::join('sales', 'sales.customer_id', '=', 'customers.id')
            ->join('sales_credits', 'sales_credits.sale_id', '=', 'sales.id')
            ->groupby('customers.id')
            ->get();
        return view('sale_reports.index', compact('price_category', 'customers'));
    }

    protected function reportOption(Request $request)
    {
        $date_range = explode("-", $request->date_range);
        $from = trim($date_range[0]);
        $to = trim($date_range[1]);
        $pharmacy['name'] = Setting::where('id', 100)->value('value');
        $pharmacy['logo'] = Setting::where('id', 105)->value('value');
        $pharmacy['address'] = Setting::where('id', 106)->value('value');
        $pharmacy['tin_number'] = Setting::where('id', 102)->value('value');
        $pharmacy['date_range'] = "From" . " " . date('j M, Y', strtotime($from)) . " " . "To" . " " . date('j M, Y', strtotime($to));

        switch ($request->report_option) {
            case 1:
                $data = $this->cashSaleDetailReport($from, $to);
                $pdf = PDF::loadView('sale_reports.cash_sale_detail_report_pdf',
                    compact('data', 'pharmacy'));
                return $pdf->stream('Cash Sale.pdf');
                break;
            case 2:
                $data = $this->cashSaleSummaryReport($from, $to);
                $pdf = PDF::loadView('sale_reports.cash_sale_summary_report_pdf',
                    compact('data', 'pharmacy'));
                return $pdf->stream('sale_summary_report.pdf');
                break;
            case 3:
                $data = $this->creditSaleDetailReport($from, $to);
                $pdf = PDF::loadView('sale_reports.credit_sale_detail_report_pdf',
                    compact('data', 'pharmacy'));
                return $pdf->stream('Credit Sale.pdf');
                break;
            case 4:
                $data = $this->creditSaleSummaryReport($from, $to);
                $pdf = PDF::loadView('sale_reports.credit_sale_summary_report_pdf',
                    compact('data', 'pharmacy'));
                return $pdf->stream('sale_summary_report.pdf');
                break;
            case 5:
                $data = $this->creditPaymentReport($from, $to);
                $pdf = PDF::loadView('sale_reports.credit_payment_report_pdf',
                    compact('data', 'pharmacy'));
                return $pdf->stream('credit_payment_report.pdf');
                break;
            case 6:
                $request->validate([
                    'customer_id' => 'required',
                ]);
                $data = $this->customerStatement($from, $to, $request->customer_id);
                $customer = Customer::where('id', $request->customer_id)->value('name');
                $pdf = PDF::loadView('sale_reports.customer_payment_statement_pdf',
                    compact('data', 'pharmacy', 'customer'));
                return $pdf->stream('customer_payment_statement.pdf');
                break;
            case 8:
                $data = $this->priceListReport($request->category);
                $view = 'sale_reports.price_list_report_pdf';
                $output = 'price_list_report.pdf';
                $inventory_report = new InventoryReportController();
                $inventory_report->splitPdf($data, $view, $output);
                break;
            case 11:
                $data = $this->saleReturnReport();
                $pdf = PDF::loadView('sale_reports.sale_return_report_pdf',
                    compact('data', 'pharmacy'));
                return $pdf->stream('sale_return_report.pdf');
                break;
            default:

        }
    }

    private function cashSaleDetailReport($from, $to)
    {
        $sale_detail = SalesDetail::whereNotIn('sale_id', DB::table('sales_credits')->pluck('sale_id'))
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '>=', $from)
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '<=', $to)
            ->get();

        $sales = array();
        $grouped_sales = array();
        foreach ($sale_detail as $item) {
            $amount = $item->amount - $item->discount;
            $vat_percent = $item->vat / $item->price;
            $sub_total = ($amount / (1 + $vat_percent));
            $vat = $amount - $sub_total;
            array_push($sales, array(
                'receipt_number' => $item->sale['receipt_number'],
                'name' => $item->currentStock['product']['name'],
                'quantity' => $item->quantity,
                'vat' => $vat,
                'discount' => $item->discount,
                'price' => $item->price,
                'amount' => $amount,
                'total_discount' => $item->sale['cost']['discount'],
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

        return $grouped_sales;
    }

    private function cashSaleSummaryReport($from, $to)
    {
        $sale_detail = DB::table('sales_details')
            ->select(DB::raw('sales.id'),
                DB::raw('sum(amount) as amount'),
                DB::raw('sum(vat) as vat'),
                DB::raw('sum(price) as price'),
                DB::raw('sum(discount) as discount'),
                DB::raw('date(date) as dates'), DB::raw('created_by'), DB::raw('name'))
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '>=', $from)
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '<=', $to)
            ->join('users', 'users.id', '=', 'sales.created_by')
            ->whereNotIn('sale_id', DB::table('sales_credits')->pluck('sale_id'))
            ->groupby('dates', 'created_by')
            ->get();

        $sale_detail_to_pdf = array();

        foreach ($sale_detail as $item) {
            $value = $item->amount - $item->discount;
            $vat_percent = $item->vat / $item->price;
            $sub_total = ($value / (1 + $vat_percent));

            array_push($sale_detail_to_pdf, array(
                'date' => $item->dates,
                'sub_total' => number_format((float)($sub_total)
                    , 2, '.', ''),
                'sold_by' => $item->name
            ));

        }

        return $sale_detail_to_pdf;
    }

    private function creditSaleDetailReport($from, $to)
    {

        $sale_detail = SalesDetail::join('sales_credits', 'sales_credits.sale_id', '=', 'sales_details.sale_id')
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '>=', $from)
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '<=', $to)
            ->get();

        $sales = array();
        $grouped_sales = array();
        foreach ($sale_detail as $item) {
            $amount = $item->amount - $item->discount;
            $vat_percent = $item->vat / $item->price;//Here VAT % is calculated.
            $sub_total = ($amount / (1 + $vat_percent));
            $vat = $amount - $sub_total;
            array_push($sales, array(
                'receipt_number' => $item->sale['receipt_number'],
                'name' => $item->currentStock['product']['name'],
                'quantity' => $item->quantity,
                'vat' => $vat,
                'discount' => $item->discount,
                'price' => $item->price,
                'amount' => $amount,
                'sub_total' => $sub_total,
                'paid' => $item->paid_amount,
                'balance' => $item->balance,
                'total_vat' => $item->sale['cost']['vat'],
                'total_discount' => $item->sale['cost']['discount'],
                'grand_total' => ($item->sale['cost']['amount']) - ($item->sale['cost']['discount']),
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

// $keys =array_keys($grouped_sales);
// dd($keys);
// foreach ($keys as $value) {
// dd($value);
// }
        return $grouped_sales;
    }

    private function creditSaleSummaryReport($from, $to)
    {
        $sale_detail = SalesDetail::join('sales_credits', 'sales_credits.sale_id', '=', 'sales_details.sale_id')
            ->select(DB::raw('sales.id'),
                DB::raw('sum(amount) as amount'),
                DB::raw('sum(vat) as vat'),
                DB::raw('sum(price) as price'),
                DB::raw('sum(discount) as discount'),
                DB::raw('date(date) as dates'), DB::raw('sales.created_by'), DB::raw('name'))
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '>=', $from)
            ->where(DB::Raw("DATE_FORMAT(date,'%m/%d/%Y')"), '<=', $to)
            ->join('users', 'users.id', '=', 'sales.created_by')
            ->groupby('dates', 'created_by')
            ->get();

        $sale_detail_to_pdf = array();

        foreach ($sale_detail as $item) {
            $value = $item->amount - $item->discount;
            $vat_percent = $item->vat / $item->price;
            $sub_total = ($value / (1 + $vat_percent));

            array_push($sale_detail_to_pdf, array(
                'date' => $item->dates,
                'sub_total' => number_format((float)($sub_total)
                    , 2, '.', ''),
                'sold_by' => $item->name
            ));

        }

        return $sale_detail_to_pdf;
    }

    private function creditPaymentReport($from, $to)
    {
        $payments = SalesCredit::join('sales', 'sales.id', '=', 'sales_credits.sale_id')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->where(DB::Raw("DATE_FORMAT(sales_credits.created_at,'%m/%d/%Y')"), '>=', $from)
            ->where(DB::Raw("DATE_FORMAT(sales_credits.created_at,'%m/%d/%Y')"), '<=', $to)
            ->where('paid_amount', '>', 0)
            ->get();
        return $payments;
    }

    private function customerStatement($from, $to, $customer_id)
    {
        $data = json_decode(Sale::join('sales_credits', 'sales_credits.sale_id', '=', 'sales.id')
            ->where(DB::Raw("DATE_FORMAT(sales_credits.created_at,'%m/%d/%Y')"), '>=', $from)
            ->where(DB::Raw("DATE_FORMAT(sales_credits.created_at,'%m/%d/%Y')"), '<=', $to)
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->where('customer_id', $customer_id)
// ->where('paid_amount','>',0)
            ->get(), true);
        $grouped_data = [];
        foreach ($data as $val) {
            if (array_key_exists('receipt_number', $val)) {
                $grouped_data[$val['receipt_number']][] = $val;
            }
        }

        return $grouped_data;
    }

    private function priceListReport($category)
    {
        $price_list_to_pdf = array();
        $price_list = PriceList::where('price_category_id', '=', $category)
            ->with(['currentStock' => function ($query) {
                $query->select('id', 'product_id', 'batch_number');
            }])
            ->whereNotNull('stock_id')
            ->get();

        foreach ($price_list as $value) {
            array_push($price_list_to_pdf, array(
                'category' => $value->priceCategory['name'],
                'name' => $value->currentStock['product']['name'],
                'batch' => $value->currentStock['batch_number'],
                'price' => $value->price
            ));
        }

        return $price_list_to_pdf;

    }

    private function saleReturnReport()
    {

        $returns = SalesReturn::join('sales_details', 'sales_details.id', '=', 'sales_returns.sale_detail_id')
            ->where('sales_details.status', '=', 3)
            ->orWhere('sales_details.status', '=', 5)
            ->get();
        foreach ($returns as $value) {
            $value->item_returned;
        }
// dd(json_decode($returns,true));
        return $returns;

    }

}
