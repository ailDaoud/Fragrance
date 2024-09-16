<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // public function getAllProducts()
    // {
    //     $products = Product::with('tags', 'category')->get();
    //     return response()->json([
    //         'success' => 1,
    //         'result' => $products,
    //         'message' => __('messages.products_retrieved_successfully'),
    //     ], 200);
    // }

    // public function getProductsByCategory($categoryId)
    // {
    //     $products = Product::where('category_id', $categoryId)->with('tags')->paginate(10);
    //     return response()->json([
    //         'success' => 1,
    //         'result' => $products,
    //         'message' => __('messages.products_retrieved_successfully'),
    //     ], 200);
    // }
    // public function getProductsByTag($tagId)
    // {
    //     $products = Product::with('category')->whereHas('tags', function ($query) use ($tagId) {
    //         $query->where('tag_id', $tagId);
    //     })->paginate(10);
    //     return response()->json([
    //         'success' => 1,
    //         'result' => $products,
    //         'message' => __('messages.products_retrieved_successfully'),
    //     ], 200);
    // }
    // public function getProductsByCategoryAndTag(Request $request)
    // {

    //     $categoryId = $request->query('category');
    //     $tagId = $request->query('tag');
    //     $limit = $request->query('limit', 10);

    //     $query = Product::query();

    //     if ($categoryId) {
    //         $query->where('category_id', $categoryId);
    //     }

    //     if ($tagId) {
    //         $query->whereHas('tags', function ($tagQuery) use ($tagId) {
    //             $tagQuery->where('tag_id', $tagId);
    //         });
    //     }

    //     $products = $query->limit($limit)->with('tags')->get();

    //     return response()->json([
    //         'success' => 1,
    //         'result' => $products,
    //         'message' => __('messages.products_retrieved_successfully'),
    //     ], 200);
    // }
    // public function getHeroProducts()
    // {
    //     $heroProducts = Product::with('tags', 'category')->where('is_hero', true)
    //         ->get();

    //     return response()->json([
    //         'success' => 1,
    //         'result' => $heroProducts,
    //         'message' => __('messages.success'),
    //     ]);
    // }
    public function getProducts(Request $request)
    {
        // Extract parameters from the request
        $categoryId = $request->query('category');
        $tagId = $request->query('tag');
        $limit = $request->query('limit'); // Default limit is 10
        $isHero = $request->query('only_hero_products', false); // Default is_hero is false

        // Build the query
        $query = Product::query();

        // Filter by category if provided
        if ($categoryId) {
            error_log('category_id');
            $query->where('category_id', $categoryId);
        }

        // Filter by tag if provided
        if ($tagId) {
            error_log('tag_id');
            $query->whereHas('tags', function ($tagQuery) use ($tagId) {
                $tagQuery->where('tag_id', $tagId);
            });
        }

        // Filter by hero products if requested
        if ($isHero) {
            error_log('is_hero');
            $query->where('is_hero', true);
        }

        // Set the relationship to be eager loaded
        $query->with('tags', 'category');

        // Get the results with the specified limit or pagination
        if ($limit) {
            error_log('limit');
            $products = $query->limit($limit)->get();
        } else {
            error_log('paginate');
            $products = $query->paginate(10); // Default pagination
        }

        // Return the JSON response
        return response()->json([
            'success' => 1,
            'result' => $products,
            'message' => __('messages.products_retrieved_successfully'),
        ], 200);
    }
}