<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function categoryPage()
    {
        return view('pages.dashboard.category-page');
    }
    public function CategoryList(Request $request)
    {
        try {

            $userId = $request->header('user_id');

            $categories = Category::where('user_id', $userId)->get();

            return response()->json([
                'data' => $categories,
                'status' => 'success',
                'message' => 'Categories retrived successfully',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal server error'
            ], 500);
        }
    }
    public function CategoryCreate(Request $request)
    {
        $userId = $request->header('user_id');
        $name = $request->name;
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|min:3|unique:categories',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'errors' => $validation->errors(),
                    'status' => 'failed',
                    'message' => 'Validation failed'
                ], 422);
            }
            $category = Category::create([
                'name' => $name,
                'user_id' => $userId,
            ]);
            if (!$category) {
                return response()->json([
                    'message' => 'Unable to create category',
                    'status' => 'failed'
                ], 500);
            }
            return response()->json(
                [
                    'message' => 'Category created successfully',
                    'status' => 'success',
                    'data' => $category,
                ],
                201
            );
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function CategoryById(Request $request)
    {
        $catId = $request->id;
        $userId = $request->header('user_id');
        try {
            $validation = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'errors' => $validation->errors(),
                    'message' => 'Validation failed',
                    'status' => 'failed'
                ], 422);
            }
            $category = Category::where('user_id', $userId)->where('id', $catId)->first();
            if (!$category) {
                return response()->json([
                    'message' => 'No category Found',
                    'status' => 'failed',
                ], 404);
            }
            return response()->json([
                'data' => $category,
                'status' => 'success',
                'message' => 'Categories retrived successfully',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function CategoryUpdate(Request $request)
    {
        $userId = $request->header('user_id');
        $name = $request->name;
        $catId = $request->id;
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|min:3',
                'id' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'errors' => $validation->errors(),
                    'status' => 'failed',
                    'message' => 'Validation failed'
                ], 422);
            }

            $category = Category::where(['id' => $catId, 'user_id' => $userId])->first();
            if (!$category) {
                return response()->json([
                    'message' => 'No such category found',
                    'status' => 'failed'
                ], 404);
            }
            $category->update(['name' => $request->name]);
            return response()->json(
                [
                    'message' => 'Category updated successfully',
                    'status' => 'success',
                    'data' => $category,
                ]
            );
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal server error'
            ], 500);
        }
    }
    public function CategoryDelete(Request $request)
    {
        $catId = $request->id;
        $userId = $request->header('user_id');
        try {
            $validation = Validator::make($request->all(), [

                'id' => 'required',
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'errors' => $validation->errors(),
                    'status' => 'failed',
                    'message' => 'Validation failed'
                ], 422);
            }

            $category = Category::where(['id' => $catId, 'user_id' => $userId])->first();
            if (!$category) {
                return response()->json([
                    'message' => 'No such category found',
                    'status' => 'failed'
                ], 404);
            }
            $category->delete();
            return response()->json(
                [
                    'message' => 'Category deleted successfully',
                    'status' => 'success',

                ]
            );
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal server error'
            ], 500);
        }
    }
}
