<?php

namespace App\Http\Controllers;
use App\Sale;

use DB;
use Illuminate\Http\Request;
use PDF;

class SaleReportController extends Controller
{
    public function index()
    {
        return view('sale_reports.index');
    }

    protected function getReport(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        switch ($request->report) {
            case 1:
                $data = $this->TotalDailySales($from, $to);
                return view('sale_reports.total_daily_sales', compact('data'));
                break;
            case 2:
                $data = $this->TotalMonthlySale($from, $to);
                return view('sale_reports.total_daily_monthly', compact('data'));
                break;
         
            default:

        }
    }

    private function TotalDailySales($from, $to)
    {
        $totalDailySales = DB::table('sales')
            ->select(DB::raw('date(created_at) date, sum(quantity*selling_price) amount'))
            ->whereRaw("date(created_at) between '". $from . "' and '". $to ."'")
            ->groupBy(DB::raw('date(created_at)'))
            ->orderBy('date','Desc')
            ->get();

        return $totalDailySales;
    }

    private function TotalMonthlySale($from, $to)
    {
        $totalMonthlySales = DB::table('sales')
            ->select(DB::raw("DATE_FORMAT(created_at, '%b %Y') month,sum(quantity*selling_price) amount"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->limit('12')
            ->get();

        return $totalMonthlySales;
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




}
