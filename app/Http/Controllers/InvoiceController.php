<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Supplier;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use View;

class InvoiceController extends Controller
{
    //
    public function index()
    {
        $invoices = Invoice::orderBy('id', 'DESC')->get();

        $suppliers = Supplier::orderBy('id', 'DESC')->get();

        return View::make('purchases.invoice_management.index',
            compact('invoices', 'suppliers'));
    }

    public function store(Request $request)
    {
        date_default_timezone_set('Africa/Nairobi');
        $date = date('Y-m-d H:i:s');
        try {

            $invoice = new  Invoice;
            $invoice->invoice_no = $request->invoice_number;
            $invoice->supplier_id = $request->supplier;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->invoice_amount = str_replace(',', '', $request->invoice_amount);
            $invoice->paid_amount = str_replace(',', '', $request->paid_amount);
            $invoice->remain_balance = str_replace(',', '', $request->balance);
            $invoice->grace_period = $request->grace_period;
            $invoice->received_status = $request->received_status;
            $invoice->payment_due_date = $request->payment_due_date;
            $invoice->remarks = $request->remarks;
            $invoice->updated_by = Auth::user()->id;
            $invoice->updated_at = $date;
            $invoice->save();

            session()->flash("alert-success", "Invoice Added Successfully!");
            return back();
        } catch (Exception $exception) {

            session()->flash("alert-danger", "Invoice Exists Already!");
            return back();

        }
    }

    public function update(Request $request)
    {
        date_default_timezone_set('Africa/Nairobi');
        $date = date('Y-m-d H:i:s');

        $invoice = Invoice::find($request->id);
        $invoice->invoice_no = $request->invoice_number;
        $invoice->supplier_id = $request->supplier;
        $invoice->invoice_date = $request->invoice_date;
        $invoice->invoice_amount = str_replace(',', '', $request->invoice_amount);
        $invoice->paid_amount = str_replace(',', '', $request->paid_amount);
        $invoice->remain_balance = str_replace(',', '', $request->balance);
        $invoice->grace_period = $request->grace_period;
        $invoice->received_status = $request->received_status;
        $invoice->payment_due_date = $request->payment_due_date;
        $invoice->remarks = $request->remarks;
        $invoice->updated_by = Auth::user()->id;
        $invoice->updated_at = $date;
        $invoice->save();

        session()->flash("alert-success", "Invoice Updated Successfully!");
        return back();
    }

    public function getInvoice(Request $request)
    {

        $from = $request->date[0];
        $to = $request->date[1];
        $invoice_history = Invoice::where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '>=', $from)
            ->where(DB::Raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), '<=', $to)
            ->get();

        foreach ($invoice_history as $value) {
            $value->supplier;
            $value->date = date('d-m-Y', strtotime($value->invoice_date));
            $value->due_date = date('d-m-Y', strtotime($value->payment_due_date));
        }
        $data = json_decode($invoice_history, true);

        return $data;
    }

}
