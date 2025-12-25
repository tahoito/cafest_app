<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\User\SettingsRequest;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('signup')) {
        return redirect()->route('user.signup');
        }
        return view('pages.user.settings');
    }

    public function store(SettingsRequest $request)
    {
        if (!$request->session()->has('signup')) {
            return redirect()->route('user.signup');
        }

        $signup = $request->session()->get('signup');

        $validated = $request->validated();

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('user_icons', 'public');
        }

        $areas = $validated['area'] ? json_decode($validated['area'], true) : [];
        $moods = $validated['mood'] ? json_decode($validated['mood'], true) : [];


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

