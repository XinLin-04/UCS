<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:user')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->hasVerifiedEmail()) {
            // dd('Redirecting to verification page'); // Debugging
            Auth::logout();

            // Store the email in the session
            $request->session()->put('unverified_email', $user->email);
    
            // Redirect back to the login page with an error message
            return redirect('/login')
                ->with('error', 'You need to verify your email address before logging in.');
        } else {
            // If the email is verified, redirect to the main page with a success message
            return redirect()->route('mainPage')->with('success', 'Login successful!');
        }
    }   
}

