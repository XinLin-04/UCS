<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function updateProfilePicture(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'profile_picture' => [
                'required', // Ensure the file is uploaded
                'image', // Ensure the file is an image
                'mimes:jpeg,png,jpg,gif', // Restrict to specific image formats
                'max:3072', // Limit file size to 3 MB (3072 KB)
            ],
        ], [
            // Custom error messages
            'profile_picture.required' => 'Please upload a profile picture.',
            'profile_picture.image' => 'The file must be an image.',
            'profile_picture.mimes' => 'Only JPEG, PNG, JPG, and GIF formats are allowed.',
            'profile_picture.max' => 'The profile picture must not exceed 3 MB.',
        ]);
    
        $user = Auth::user();
    
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_pictures'), $filename);
    
            // Delete the old profile picture if it exists
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }
    
            // Save the new profile picture path
            $user->profile_picture = 'uploads/profile_pictures/' . $filename;
            $user->save();
        }
    
        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
}