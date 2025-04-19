<?php
namespace App\Http\Controllers;

use App\Models\Comment;

class CommentController extends Controller
{
    //Policy
    public function index()
    {
        $comment = Comment::all();
        return view('xxx.index', ['comments' => $comment]);
    }

    public function show()
    {
        $comment = Comment::all();
        return view('xxx.show', ['comments' => $comment]);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
    }
}
