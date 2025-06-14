<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function productPage()
    {
        return view('pages.dashboard.product-page');
    }
    public function ProductCreate(Request $request)
    {
        try {

            $userId = $request->header('user_id');
            $validation = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'price' => 'required',
                'qty' => 'required',
                'category_id' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'status' => 'failed',
                    'errors' => $validation->errors(),
                ], 422);
            }
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $userId . '_' . time() . '_' . $file->getClientOriginalName();
                $imagePath =  $file->storeAs($userId, $fileName, 'public');
                //$imagePath = Storage::disk('public')->putFileAs($userId, $file, $fileName);
            }

            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'unit' => $request->qty,
                'img_url' => $imagePath,
                'category_id' => $request->category_id,
                'user_id' => $userId,
            ]);

            return response()->json([
                'message' => 'Product created successfully',
                'data' => $product,
                'status' => 'success',
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'There is something went wrong',
                'status' => 'failed',
            ], 500);
        }
    }
    public function ProductList(Request $request)
    {
        $userId = $request->header('user_id');
        try {

            $products = Product::where('user_id', $userId)->get();
            return response()->json([
                'message' => 'Products retrived successfully',
                'status' => 'success',
                'data' => $products,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 'failed',
            ], 500);
        }
    }
    public function ProductById(Request $request)
    {
        $userId = $request->header('user_id');
        try {
            $validation = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'status' => 'failed',
                    'errors' => $validation->errors(),
                ], 422);
            }
            $product = Product::where(['user_id' => $userId, 'id' => $request->id])->first();

            if (!$product) {
                return response()->json([
                    'message' => 'Product not found',
                    'status' => 'failed'
                ], 404);
            }
            return response()->json([
                'message' => 'Product retrived successfully',
                'status' => 'success',
                'data' => $product,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 'failed',
            ], 500);
        }
    }

    public function ProductUpdate(Request $request)
    {
        $userId =  $request->header('user_id');
        $oldImagePath = $request->imagePath;
        try {
            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'price' => 'required',
                'qty' => 'required',
                'category_id' => 'required',

            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation fails',
                    'status' => 'failed',
                    'errors' => $validation->errors(),

                ], 422);
            }

            if ($oldImagePath != null && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $userId . '_' . time() . $file->getClientOriginalName();
                $imagePath = $file->storeAs($userId, $fileName, 'public');
            }
            $product = Product::where(['user_id' => $userId, 'id' => $request->id])->update([
                'name' => $request->name,
                'price' => $request->price,
                'unit' => $request->qty,
                'category_id' => $request->category_id,
                'user_id' => $userId,
                'img_url' => $imagePath,
            ]);
            if (!$product) {
                return response()->json([
                    'message' => 'Failed to update product',
                    'status' => 'failed'
                ], 500);
            }
            return response()->json([
                'message' => 'Product updated successfully',
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 'failed',
            ], 500);
        }
    }
    public function ProductDelete(Request $request)
    {
        $userId = $request->header('user_id');
        $oldImagePath = $request->imagePath;

        try {
            $validation = Validator::make($request->all(), [
                'id' => 'required',


            ]);
            if ($validation->fails()) {
                return response()->json([
                    'message' => 'Validation fails',
                    'status' => 'failed',
                    'errors' => $validation->errors(),

                ], 422);
            }

            if ($oldImagePath != null && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }
            $deleted =  Product::where(['user_id' => $userId, 'id' => $request->id])->delete();
            if (!$deleted) {
                return  response()->json([
                    'message' => 'Product not found or there is something went wrong while deleting the product',
                    'status' => 'failed',
                ], 404);
            }
            return response()->json([
                'message' => 'Product deleted successfully',
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
