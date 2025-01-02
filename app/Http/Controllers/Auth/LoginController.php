<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
    protected $redirectTo = '/welcome';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

        /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(\Illuminate\Http\Request $request)
    {
        // Use the 'web' guard explicitly
        return Auth::guard('web')->attempt(
            $this->credentials($request), // Credentials from the request
            $request->filled('remember')  // Remember me checkbox
        );
    }

    /**
     * Override the credentials method if needed.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(\Illuminate\Http\Request $request)
    {
        // Default credentials logic
        return $request->only($this->username(), 'password');
    }

   // Override redirectTo method to handle role-based redirection
//    protected function redirectTo()
//    {
//        $user = Auth::user();
//        if ($user->is_admin) {
//            return '/admin/dashboard';
//        } else {
//            return '/user/dashboard';
//        }
//    }
}
