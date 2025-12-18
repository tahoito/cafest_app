<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('user')->attempt($credential)) {
            $request->session()->regenerate();

            return redirect()->intended(route('top'));
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが違います。',
        ])->onlyInput('email');
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