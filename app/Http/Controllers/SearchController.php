<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // Or whatever model you're searching
use App\Models\Product; // If you want to search products

class SearchController extends Controller
{
    //


    public function index(Request $request)
    {
        // Get the search term from the query string
        $searchTerm = $request->input('query');

        // Example search logic for posts
        $posts = Post::where('title', 'like', '%' . $searchTerm . '%')->get();

        // Example search logic for products (you can use one or both depending on your case)
        $products = Product::where('name', 'like', '%' . $searchTerm . '%')->get();

        // Pass the results to the view
        return view('search.results', compact('posts', 'products'));
    }
}
