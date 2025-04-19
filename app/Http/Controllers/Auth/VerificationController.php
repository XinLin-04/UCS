<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['verify', 'resendForUnverifiedUser']); // Exclude 'verify' and 'resendForUnverifiedUser'
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
    
    public function resendForUnverifiedUser(Request $request)
    {
    // Retrieve the email from the session
    $email = $request->session()->get('unverified_email');

    if (!$email) {
        return redirect('/login')->with('error', 'No email found for verification. Please log in again.');
    }

    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        return redirect('/login')->with('error', 'User not found.');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect('/login')->with('status', 'Your email is already verified. Please log in.');
    }

    $user->sendEmailVerificationNotification();

    return redirect('/login')->with('resent', 'A new verification link has been sent to your email address.');
    }

    public function verify(Request $request)
    {
        $user = \App\Models\User::find($request->route('id'));
    
        if (!$user) {
            return redirect('/login')->with('error', 'User not found.');
        }
    
        if ($user->hasVerifiedEmail()) {
            return redirect('/login')->with('status', 'Your email is already verified. Please log in.');
        }
    
        $user->markEmailAsVerified();
        
        return redirect('/login')->with('status', 'Your email has been successfully verified. Please log in.');
    }
}
