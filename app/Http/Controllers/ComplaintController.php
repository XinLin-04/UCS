<?php
namespace App\Http\Controllers;

use App\Models\Complaint;

class ComplaintController extends Controller
{
    //Policy
    public function index()
    {
        $complaint = Complaint::all();
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

}
