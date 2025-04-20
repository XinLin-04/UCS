<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint; // Replace with your actual model name

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        if (empty($query)) {
            // Return all recent posts if the query is blank
            $complaints = Complaint::with('user')->latest()->get();
        } else {
            // Search for posts matching the query
            $complaints = Complaint::where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->with('user')
                ->get();
        }
    
        return response()->json($complaints);
    }
}