<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function customerPage()
    {
        return view('pages.dashboard.customer-page');
    }

    public function CustomerList(Request $request)
    {
        $userId = $request->header('user_id');
        try {
            $customers = Customer::where('user_id', $userId)->get();
            return response()->json([
                'data' => $customers,
                'message' => 'Customers retrived successfully',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal server error',
            ], 500);
        }
    }

    public function CustomerById(Request $request)
    {
        $userId = $request->header('user_id');
        $customerId = $request->id;
        try {

            $validation = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation fails',
                    'errors' => $validation->errors(),
                    'status' => 'failed'
                ], 422);
            }
            $customer = Customer::where(['user_id' => $userId, 'id' => $customerId])->first();
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found',
                    'status' => 'failed',

                ], 404);
            }
            return response()->json([
                'data' => $customer,
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'status' => 'failed',
            ], 500);
        }
    }

    public function CustomerCreate(Request $request)
    {
        $userId = $request->header('user_id');
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email:filter_unicode|unique:customers',
                'mobile' => 'required|min:11',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validation->errors(),
                    'status' => 'failed',
                ], 422);
            }
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $userId,
            ]);
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer is not created due to internal serve error',
                    'status' => 'failed',
                ], 500);
            }
            return response()->json([
                'message' => 'Customer created successfully',
                'status' => 'success'
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'status' => 'failed',
            ], 500);
        }
    }

    function CustomerUpdate(Request $request)
    {
        $userId = $request->header('user_id');
        $customerId = $request->id;
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'email' => 'required|email:filter_unicode',
                'mobile' => 'required|min:11',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validation->errors(),
                    'status' => 'failed',
                ], 422);
            }
            $customer = Customer::where(['user_id' => $userId, 'id' => $customerId])->first();
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found',
                    'status' => 'failed',
                ], 404);
            }

            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $userId,
            ]);
            return response()->json([
                'message' => 'Customer update successfully',
                'status' => 'success'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'status' => 'failed',
            ], 500);
        }
    }

    public function CustomerDelete(Request $request)
    {
        $userId = $request->header('user_id');
        $customerId = $request->id;
        try {

            $validation = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation fails',
                    'errors' => $validation->errors(),
                    'status' => 'failed'
                ], 422);
            }
            $customer = Customer::where(['user_id' => $userId, 'id' => $customerId])->first();
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found',
                    'status' => 'failed',

                ], 404);
            }
            $customer->delete();
            return response()->json([
                'message' => 'Customer deleted successfully',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'status' => 'failed',
            ], 500);
        }
    }
}
