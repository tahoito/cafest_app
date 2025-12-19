<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('user')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('user.top');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が正しくありません',
        ]);
    }


    public function showLogin()
    {
        return view('pages.user.auth.login');
    }           

    public function showSignup()
    {
        return view('pages.user.auth.signup');
    }  
    
    
    public function signup(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return redirect()->route('user.login');
    }       

}