<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    //Gate
    public function create()
    {
        if (Gate::allows('isUser')) {
            dd('Create successfully!');
        } else {
            dd('Create fail!');
        }

    }
    public function edit()
    {
        if (Gate::allows('isUser')) {
            dd('Update successfully!');
        } else {
            dd('Update fail!');
        }
    }
    public function delete()
    {
        if (Gate::allows('isUser', 'isAdmin')) {
            dd('Delete successfully!');
        } else {
            dd('Delete fail!');
        }
    }
}
