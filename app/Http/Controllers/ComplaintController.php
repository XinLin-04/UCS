<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ComplaintUpdated;
use App\Notifications\ComplaintDeleted;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the complaints.
     */
    public function index()
    {
        $complaints = Complaint::with('user')->latest()->get();
        return view('mainPage', compact('complaints'));
    }

    /**
     * Store a newly created complaint.
     */
    public function store(Request $request)
    {
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
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'admin_note' => 'required_if:user_role,admin|string'
        ]);

        // Check if user is authorized
        if (Auth::user()->role === 'admin' || Auth::id() === $complaint->user_id) {
            $complaint->title = $request->title;
            $complaint->content = $request->content;
            
            // If admin is updating, save explanation note
            if (Auth::user()->role === 'admin') {
                // Create notification for the user with the admin note
                $complaint->user->notify(new ComplaintUpdated($complaint, $request->admin_note));
            }
            
            $complaint->save();
            
            return redirect()->route('complaints.index')
                ->with('success', 'Complaint updated successfully.');
        }
        
        return redirect()->route('complaints.index')
            ->with('error', 'You are not authorized to update this complaint.');
    }

    /**
     * Remove the specified complaint.
     */
    public function destroy(Request $request, Complaint $complaint)
    {
        $request->validate([
            'admin_note' => 'required_if:user_role,admin|string'
        ]);

        // Check if user is authorized
        if (Auth::user()->role === 'admin' || Auth::id() === $complaint->user_id) {
            // If admin is deleting, save explanation note
            if (Auth::user()->role === 'admin') {
                // Create notification for the user with the admin note
                $complaint->user->notify(new ComplaintDeleted($request->admin_note));
            }
            
            $complaint->delete();
            
            return redirect()->route('complaints.index')
                ->with('success', 'Complaint deleted successfully.');
        }
        
        return redirect()->route('complaints.index')
            ->with('error', 'You are not authorized to delete this complaint.');
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