<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for a specific complaint.
     */
    public function index(Complaint $complaint)
    {
        $comments = $complaint->comments()
            ->with('user')
            ->latest()
            ->get();
            
        return response()->json($comments);
    }

    /**
     * Store a newly created comment (API version).
     */
    public function storeApi(Request $request, Complaint $complaint)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id();
        $comment->complaint_id = $complaint->id;
        $comment->save();

        return response()->json($comment->load('user'), 201);
    }
    
    /**
     * Store a newly created comment (form submission).
     */
    public function store(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'content' => 'required|string'
        ]);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id();
        $comment->complaint_id = $request->complaint_id;
        $comment->save();

        return redirect()->route('complaints.show', $request->complaint_id)
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        // Check if user is authorized
        if (Auth::user()->role === 'admin' || Auth::id() === $comment->user_id) {
            $comment->content = $request->content;
            $comment->save();
            
            if ($request->expectsJson()) {
                return response()->json($comment);
            }
            
            return redirect()->route('complaints.show', $comment->complaint_id)
                ->with('success', 'Comment updated successfully.');
        }
        
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return redirect()->route('complaints.show', $comment->complaint_id)
            ->with('error', 'You are not authorized to update this comment.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        // Check if user is authorized
        if (Auth::user()->role === 'admin' || Auth::id() === $comment->user_id) {
            $complaintId = $comment->complaint_id;
            $comment->delete();
            
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Comment deleted successfully']);
            }
            
            return redirect()->route('complaints.show', $complaintId)
                ->with('success', 'Comment deleted successfully.');
        }
        
        if (request()->expectsJson()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return redirect()->route('complaints.show', $comment->complaint_id)
            ->with('error', 'You are not authorized to delete this comment.');
    }
}