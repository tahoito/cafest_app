<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('signup'))
        {
            return view('pages.user.settings');
        }
        return view ('pages.user.settings');
    }

    public function store(Request $request)
    {
        if (!$request->session()->has('signup')) {
            return redirect()->route('user.signup');
        }

        $signup = $request->session()->get('signup');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'nullable|string|max:50',
            'mood' => 'nullable|string|max:50',
            'icon' => 'nullable|image|max:2048', // 画像アップするなら
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('user_icons', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $signup['email'],
            'password' => Hash::make($signup['password']),
            'area' => $validated['area'] ?? null,
            'mood' => $validated['mood'] ?? null,
            'icon_path' => $iconPath,
        ]);

        Auth::guard('user')->login($user);
        $request->session()->regenerate();
        $request->session()->forget('signup');

        return redirect()->route('user.top');
    }
}

