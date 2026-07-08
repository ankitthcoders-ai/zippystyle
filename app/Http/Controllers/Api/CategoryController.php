<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::all();
        return response()->json([
            'message' => 'Categories API',
            'categories' => $categories,
        ]);
    }

    public function subCategories(Request $request)
    {
        $query = SubCategory::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $subCategories = $query->get();

        return response()->json([
            'message' => 'Sub categories API',
            'sub_categories' => $subCategories,
        ]);
    }
}
