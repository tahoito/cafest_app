<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.store.auth.login');
    }

    public function showSignup()
    {
        return view('pages.store.auth.signup');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('store')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('store.top');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が正しくありません',
        ]);
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:stores,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 仮登録（DB作らない）
        $request->session()->put('signup', [
            'email' => $validated['email'],
            'password' => $validated['password'], // 後でHash化する
        ]);

        return redirect()->route('store.settings');
    }
}
