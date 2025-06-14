<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function invoicePage()
    {
        return view('pages.dashboard.invoice-page');
    }
    public function InvoiceCreate(Request $request)
    {
        $userId = $request->header('user_id');
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'total' => 'required',
                'vat' => 'required',
                'payable' => 'required',
                'customer_id' => 'required',
                'products' => 'required',
            ]);
            if ($validation->fails()) {
                throw new \Illuminate\Validation\ValidationException($validation);
            }

            $invoice = Invoice::create([
                'total' => $request->total,
                'discount' => $request->discount,
                'vat' => $request->vat,
                'payable' => $request->payable,
                'customer_id' => $request->customer_id,
                'user_id' => $userId,

            ]);
            if (!$invoice) {
                throw new \Exception('Failed to create Invoice');
            }

            $products = $request->products;
            //dd($products);
            foreach ($products as $eachProduct) {
                $validation = Validator::make($eachProduct, [
                    'product_id' => 'required',
                    'qty' => 'required',
                    'sale_price' => 'required'
                ]);
                if ($validation->fails()) {
                    throw new \Illuminate\Validation\ValidationException($validation);
                }
                $invoice_product = InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $eachProduct['product_id'],
                    'user_id' => $userId,
                    'qty' =>  $eachProduct['qty'],
                    'sale_price' => $eachProduct['sale_price'],
                ]);
                if (!$invoice_product) {
                    throw new \Exception('Failed to create invoice details.');
                }
                // product update
                $product = Product::where(['user_id' => $userId, 'id' => $eachProduct['product_id']])->first();
                $qty = (int) $product->unit - (int) $eachProduct['qty'];
                $product->update(['unit' => $qty]);
                //product update close
            }
            DB::commit();
            return response()->json([
                'message' => 'Invoice and Invoice details created successfully',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'message' => 'Validation error',
                    'status' => 'failed',
                    'errors' => $e->errors(),
                ], 422);
            }
            return response()->json([
                'message' => 'Something went wrong' . ' ' . $e->getMessage(),
                'status' => 'failed',
            ], 500);
        }
    }

    public function InvoiceDetails(Request $request)
    {
        $userId = $request->header('user_id');
        try {
            $invoiceId = $request->invoice_id;
            $validation = Validator::make($request->all(), [
                'invoice_id' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'status' => 'failed',
                    'errors' => $validation->errors(),
                ], 422);
            }
            $invoice = Invoice::where(['user_id' => $userId, 'id' => $invoiceId])->with('customer')->first();

            $invoice_products = $invoice->invoice_products()->with('product')->get();


            $total = $invoice->total;
            $vat = $invoice->vat;
            $payable = $invoice->payable;
            $discount = $invoice->discount;
            $data = array(
                'invoice' => $invoice,

                'invoice_products' => $invoice_products,
                'total' => $total ?? 0,
                'vat' => $vat ?? 0,
                'payable' => $payable ?? 0,
                'discount' => $discount ?? 0,
            );
            return response()->json([
                'message' => 'Invoice retrieved successfully',
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'There is something wrong',
                'status' => 'failed',
            ], 500);
        }
    }
    public function InvoiceList(Request $request)
    {
        $userId = $request->header('user_id');
        try {
            $invoices = Invoice::where('user_id', $userId)->with('customer')->get();
            return response()->json([
                'message' => 'Invoice retrieved successfully',
                'data' => $invoices,
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 'failed'
            ], 500);
        }
    }
    public function InvoiceDelete(Request $request)
    {
        $userId = $request->header('user_id');
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'invoice_id' => 'required',
            ]);
            if ($validation->fails()) {
                throw new \Illuminate\Validation\ValidationException($validation);
            }
            $invoice = Invoice::where(['id' => $request->invoice_id, 'user_id' => $userId])->first();
            if (!$invoice) {
                throw new \Exception('Invoice not found');
            }

            $invoice_products = InvoiceProduct::where(['user_id' => $userId, 'invoice_id' => $invoice->id])->get();
            foreach ($invoice_products as $invoice_product) {

                $invoice_product->delete();
            }
            $deleted = $invoice->delete();

            DB::commit();
            return response()->json([
                'message' => 'Invoice deleted successfully',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'message' => 'Validation error',
                    'status' => 'failed',
                    'errors' =>  $e->errors(),
                ], 422);
            }
            return response()->json([
                'message' => 'Something went wrong' . ' ' . $e->getMessage(),
                'status' => 'failed',
            ], 500);
        }
    }
}
