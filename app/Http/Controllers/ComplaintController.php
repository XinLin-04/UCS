<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the complaints.
     */
    public function index()
    {
        $complaints = Complaint::with('user')
            ->withCount('comments')
            ->latest()
            ->get();

        return view('mainPage', compact('complaints'));
    }

    /**
     * Display the specified complaint.
     */
    public function show(Complaint $complaint)
    {
        $complaint->load('user');
        $comments = $complaint->comments()
            ->with('user')
            ->latest()
            ->get();

        return view('complaintDetail', compact('complaint', 'comments'));
    }

        /**
     * Store a newly created complaint.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Complaint::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $complaint = new Complaint();
        $complaint->title = $request->title;
        $complaint->content = $request->content;
        $complaint->user_id = Auth::id();
        $complaint->save();

        return redirect()->route('complaints.index')
            ->with('success', 'Complaint created successfully.');
    }

    /**
     * Update the specified complaint.
     */
    public function update(Request $request, Complaint $complaint)
    {
        $this->authorize('update', $complaint);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $complaint->title = $request->title;
        $complaint->content = $request->content;
        $complaint->save();

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Complaint updated successfully.');
    }

    /**
     * Remove the specified complaint from storage.
     */
    public function destroy(Request $request, Complaint $complaint)
    {
        $this->authorize('delete', $complaint); 
        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', 'Complaint deleted successfully.');
    }

    /**
     * Get filtered complaints for API.
     */
    public function getFiltered(Request $request)
    {
        $this->authorize('viewAny', Complaint::class);
        $filter = $request->query('filter', 'recent');
        $query = Complaint::with('user')->withCount('comments');

        switch ($filter) {
            case 'recent':
                $query->latest();
                break;

            case 'week':
                $weekStart = Carbon::now()->subWeek();
                $query->where('created_at', '>=', $weekStart)
                    ->orderByDesc('comments_count');
                break;

            case 'month':
                $monthStart = Carbon::now()->subMonth();
                $query->where('created_at', '>=', $monthStart)
                    ->orderByDesc('comments_count');
                break;

            case 'comments':
                $query->orderByDesc('comments_count');
                break;

            default:
                $query->latest();
        }

        $complaints = $query->get();

        return response()->json($complaints);
    }

    /**
     * Get a specific complaint details for API.
     */
    public function getComplaint(Complaint $complaint)
    {
        $complaint->load('user');
        return response()->json($complaint);
    }

    /**
     * Get complaints for the authenticated user.
     */
    public function userComplaints()
    {
        $this->authorize('viewAny', Complaint::class); 
        $complaints = Complaint::where('user_id', Auth::id())
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($complaints);
    }

    public function apiIndex(Request $request)
{
    $complaints = Complaint::with('user')
        ->withCount('comments')
        ->latest()
        ->get();

    return response()->json($complaints);
}
}


