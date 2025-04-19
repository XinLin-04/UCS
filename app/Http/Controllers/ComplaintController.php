<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\ComplaintUpdated;
use App\Notifications\ComplaintDeleted;
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
        $this->authorize('view', $complaint);

        $complaint->load('user');
        $comments = $complaint->comments()
            ->with('user')
            ->latest()
            ->get();

        return view('complaintDetail', compact('complaint', 'comments'));
    }

    /**
     * Get filtered complaints for API.
     */
    public function getFiltered(Request $request)
    {
        $filter = $request->query('filter', 'recent');
        $query = Complaint::with('user')->withCount('comments');

        switch ($filter) {
            case 'recent':
                $query->latest();
                break;

            case 'week':
                $weekStart = Carbon::now()->subWeek();
                $query->where('created_at', '>=', $weekStart)
                      ->orderByDesc(DB::raw('comments_count'));
                break;

            case 'month':
                $monthStart = Carbon::now()->subMonth();
                $query->where('created_at', '>=', $monthStart)
                      ->orderByDesc(DB::raw('comments_count'));
                break;

            case 'comments':
                $query->orderByDesc(DB::raw('comments_count'));
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
        $this->authorize('view', $complaint);

        $complaint->load('user');
        return response()->json($complaint);
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
            'admin_note' => 'required_if:user_role,admin|string'
        ]);

        $complaint->title = $request->title;
        $complaint->content = $request->content;

        if (Auth::user()->role === 'admin') {
            $complaint->user->notify(new ComplaintUpdated($complaint, $request->admin_note));
        }

        $complaint->save();

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Complaint updated successfully.');
    }

    /**
     * Remove the specified complaint.
     */
    public function destroy(Request $request, Complaint $complaint)
    {
        $this->authorize('delete', $complaint);

        $request->validate([
            'admin_note' => 'required_if:user_role,admin|string'
        ]);

        if (Auth::user()->role === 'admin') {
            $complaint->user->notify(new ComplaintDeleted($request->admin_note));
        }

        $complaint->delete();

        return redirect()->route('complaints.index')
            ->with('success', 'Complaint deleted successfully.');
    }

    /**
     * Get complaints for the authenticated user.
     */
    public function userComplaints()
    {
        $complaints = Auth::user()->complaints()->latest()->get();
        return response()->json($complaints);
    }
}
