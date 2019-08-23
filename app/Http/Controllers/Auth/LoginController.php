<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\User;
use App\Events\LoginEvent;

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
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */

    public function showLoginForm()
    {
        $roles=Role::all();
       return view('auth.login')->with('roles',$roles);
    }

    public function username()
    {
        return 'name';
    }


    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        // ここに追加したい処理を書く
 
        $user->online = 1;

        $user->save();

        broadcast(new LoginEvent())->toOthers();
        // ログイン後のリダイレクト
        // return redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->online = 0;
        $user->save();

        broadcast(new LoginEvent())->toOthers();        

        $this->guard()->logout();

        $request->session()->invalidate();



        return $this->loggedOut($request) ?: redirect('/');
    }    

}
