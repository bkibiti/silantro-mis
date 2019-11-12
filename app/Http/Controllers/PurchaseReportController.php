<?php

namespace App\Http\Controllers;

use App\GeneralSetting;
use App\PriceCategory;
use App\PriceList;
use App\OrderDetail;
use App\Category;
use App\Order;
use App\Invoice;
use App\currentStock;
use App\GoodsReceiving;
use App\Supplier;
use FPDF;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use PDFMerger;
use View;

ini_set('max_execution_time', 300);
set_time_limit(300);
ini_set('memory_limit','128M');

class PurchaseReportController extends Controller
{

    public function index()
    {
        $price_category = PriceCategory::all();
        $products = DB::table('product_ledger')
            ->select('product_id', 'product_name')
            ->groupby('product_id', 'product_name')
            ->get();

        $category = Category::all();
        $pharmacy = GeneralSetting::all();
        $invoices = Invoice::all();
        $orders = Order::all();
        $order_details= OrderDetail::all();
        $current_stock = currentStock::all();
        $suppliers = Supplier::all();
        $material_received = GoodsReceiving::all();
        $settings = GeneralSetting::all();
      
          return View::make('purchases_reports.index', (compact('order_details', 'suppliers', 'orders', 'price_category', 'buying_prices', 'current_stock', 'item_stocks', 'invoices','pharmacy','category','material_received','settings')));
    }

    protected function reportOption(Request $request){
    	
    	switch ($request->report_option) {
    		case 1:
				$data_og = $this->materialReceivedReport();
        	    $view = 'purchases_reports.material_received_report_pdf';
                $output = 'material_received_report.pdf';
                $report = new InventoryReportController();
                $report->splitPdf($data_og, $view, $output);
    			break;
			case 2:
			    $data_og = $this->InvoiceSummaryReport();
        	    $view = 'purchases_reports.invoice_summary_report_pdf';
                $output = 'invoice_summary_report.pdf';
                $report = new InventoryReportController();
                $report->splitPdf($data_og, $view, $output);
    			break;

    			break;
			case 3:
    			break;
    	}
    }

    public function materialReceivedReport(){
    	$datas = GoodsReceiving::all();
    	return $datas;
    }

     public function InvoiceSummaryReport(){
    	$datas = Invoice::all();
    	return $datas;
    }

     public function InvoiceDetailsReport(){
    	$datas = Invoice::all();
    	return $datas;
    }





   

}
