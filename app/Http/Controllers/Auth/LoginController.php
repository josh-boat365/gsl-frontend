<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //        /**
    //  * The user has been authenticated.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  mixed  $user
    //  * @return mixed
    //  */
    // protected function authenticated(Request $request, $user)
    // {
    //     $userApp = [];
    //     $userApp = \DB::table('user_apps')
    //             ->where('user_id',\Auth::user()->id)
    //             ->where('app_id',6)
    //             ->get();

    //     if( !isset($userApp[0]) ){
    //     $message = 'User Not Enrolled On Application';

    //             // Log the user out.
    //             $this->logout($request);

    //             // Return them to the log in form.
    //             return redirect()->back()
    //                 ->withInput($request->only($this->username(), 'remember'))
    //                 ->withErrors([
    //                     // This is where we are providing the error message.
    //                     $this->username() => $message,
    //                 ]);
    //     }

    // }
}
