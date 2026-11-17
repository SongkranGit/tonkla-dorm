<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
     public function username()
     {
         return 'username';
     }

    protected function authenticated(Request $request, $user)
    {
        $branch = Branch::where('id', $user->branch_id)->first();
        if(Auth::user()->hasRole('user') ){
            $request->session()->put('current_branch_id', $user->branch_id);
            $request->session()->put('current_branch_name', $branch->name);
            $request->session()->put('should_show_branch_list', false);
        }else{
            $request->session()->put('should_show_branch_list', true);
        }
    }
}
