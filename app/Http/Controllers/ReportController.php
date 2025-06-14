<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reportPage()
    {
        return view('pages.dashboard.report-page');
    }
    public function SalesReport(Request $request)
    {
        $userId = $request->header('user_id');
        $fromDate = date('Y-m-d', strtotime($request->FromDate));
        $toDate = date('Y-m-d', strtotime($request->ToDate));

        $invoice = Invoice::where('user_id', $userId)->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)->with('customer')->get();
        $total = $invoice->sum('total');
        $vat = $invoice->sum('vat');
        $discount = $invoice->sum('discount');
        $payable = $invoice->sum('payable');
        $data = [
            'invoice' => $invoice,
            'total' => $total ?? 0,
            'vat' => $vat ?? 0,
            'discount' => $discount ?? 0,
            'payable' => $payable ?? 0,
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ];

        $pdf = Pdf::loadView('reports.SalesReport', $data);
        // return $pdf->download('sales_report.pdf');
        if ($request->has('download')) {
            return $pdf->download('sales_report.pdf');
        }
        return $pdf->stream('sales_report.pdf');
    }
    public function StockReport(Request $request)
    {
        $userId = $request->header('user_id');
        $fromDate = date('Y-m-d', strtotime($request->PFromDate));
        $toDate = date('Y-m-d', strtotime($request->PToDate));

        $product = Product::where('user_id', $userId)->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)->get();
        $total =  $product->count();

        $data = [
            'total' => $total,
            'product' => $product,
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ];

        $pdf = Pdf::loadView('reports.StockReport', $data);
        // return $pdf->download('sales_report.pdf');
        if ($request->has('download')) {
            return $pdf->download('stock_report.pdf');
        }
        return $pdf->stream('stock_report.pdf');
    }
    public function CustomerReport(Request $request)
    {
        $userId = $request->header('user_id');
        $fromDate = date('Y-m-d', strtotime($request->FromDate));
        $toDate = date('Y-m-d', strtotime($request->ToDate));

        $customer = Customer::where('user_id', $userId)->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)->get();
        $total =  $customer->count();

        $data = [
            'total' => $total,
            'customer' => $customer,
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ];

        $pdf = Pdf::loadView('reports.CustomerReport', $data);
        // return $pdf->download('sales_report.pdf');
        if ($request->has('download')) {
            return $pdf->download('customer_report.pdf');
        }
        return $pdf->stream('customer_report.pdf');
    }
}
