<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
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
