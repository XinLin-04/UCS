<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{

    //Policy
    public function index()
    {
        $complaints = Complaint::all();
        return view('xxx.index', ['complaints' => $complaint]);
    }

    public function show()
    {
        $complaint = Complaint::all();
        return view('xxx.show', ['complaints' => $complaint]);
    }

    public function destroy($id)
    {
        $complaint = Complaint::find($id);
        $complaint->delete();
    }

    //Gate
    public function create(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        
        $complaint = Complaint::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => auth()->id()
        ]);
        
        // // Create a new complaint instance and save it to the database
        $complaint = new Complaint();
        
        // if (Gate::allows('isUser')) {
        //     dd('Create successfully!');
        // } else {
        //     dd('Create fail!');
        // }

    }
    public function edit()
    {
        
        // if (Gate::allows('isUser')) {
        //     dd('Update successfully!');
        // } else {
        //     dd('Update fail!');
        // }
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
