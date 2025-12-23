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
            'name' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string'],
            'mood' => ['nullable', 'string'],
            'icon' => ['nullable', 'image', 'max:2048'],
        ]);

        


        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('user_icons', 'public');
        }

        $areas = $validated['area'] ? json_decode($validated['area'], true) : [];
        $moods = $validated['mood'] ? json_decode($validated['mood'], true) : [];

        if (!is_array($areas) || !is_array($moods)){
            return back()->withErrors([
                'area' => 'Invalid area selection.',
                'mood' => 'Invalid mood selection.',
            ])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $signup['email'],
            'password' => Hash::make($signup['password']),
            'favorite_areas' => $areas,
            'favorite_moods' => $moods,
            'icon_path' => $iconPath,
        ]);

        Auth::guard('user')->login($user);
        $request->session()->regenerate();
        $request->session()->forget('signup');

        return redirect()->route('user.top');
    }
}

