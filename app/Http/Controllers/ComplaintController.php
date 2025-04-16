<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Constructor to ensure that all controller methods require authentication
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new complaint
     */
    public function create()
    {
        return view('complaints.create');
    }

    /**
     * Display a listing of all complaints
     */
    public function index()
    {
        $complaints = Complaint::with('user')->latest()->get();
        return view('complaints.index', compact('complaints'));
    }

    /**
     * Store a newly created complaint in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $complaint = new Complaint([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);
        
        $complaint->save();

        return redirect()->route('mainPage')->with('success', 'Complaint posted successfully!');
    }

    /**
     * Get user's complaints
     */
    public function userComplaints()
    {
        $complaints = Complaint::where('user_id', Auth::id())->latest()->get();
        return response()->json($complaints);
    }
}