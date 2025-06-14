<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
     public function dashboardPage()
     {
          return view('pages.dashboard.dashboard-page');
     }
     public function DashboardList(Request $request)
     {
          try {
               $userId = $request->header('user_id');
               $today = date('Y-m-d');
               $currentMonth = date('m');
               $currentYear = date('Y');
               $todaySale = Invoice::where('user_id', $userId)->whereDate('created_at', $today)->sum('total');
               $thisMonthSale = Invoice::where('user_id', $userId)->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->sum('total');

               $product = Product::where('user_id', $userId)->count();
               $category = Category::where('user_id', $userId)->count();
               $customer = Customer::where('user_id', $userId)->count();

               $invoices = Invoice::where('user_id', $userId)->get();
               $invoiceTotal = $invoices->count();
               $total = $invoices->sum('total');
               $vat = $invoices->sum('vat');
               $payable = $invoices->sum('payable');
               return response()->json([
                    'data' => [
                         'product' => $product,
                         'category' => $category,
                         'customer' => $customer,
                         'invoice' => $invoiceTotal,
                         'total' => $total,
                         'vat' => $vat,
                         'payable' => $payable,
                         'today_sale' => $todaySale,
                         'this_month_sale' => $thisMonthSale,
                    ],
                    'message' => 'Data retrieve successfully',
                    'status' => 'success',
               ]);
          } catch (\Throwable $e) {
               return response()->json([
                    'message' => 'Something went wrong',
                    'status' => 'failed',
               ], 500);
          }
     }
}
