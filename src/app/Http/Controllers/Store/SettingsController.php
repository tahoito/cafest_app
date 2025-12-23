<?php 

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('signup'))
        {
            return view('pages.store.settings');
        }
        return view ('pages.store.settings');
    }

    public function store(Request $request)
    {
        if (!$request->session()->has('signup')) {
            return redirect()->route('store.signup');
        }

        $signup = $request->session()->get('signup');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:50',
            'mood' => 'nullable|string|max:50',
        ]);


        $user = Store::create([
            'name' => $validated['name'],
            'email' => $signup['email'],
            'password' => Hash::make($signup['password']),
            'address' => $validated['address'] ?? null,
            'area' => $validated['area'] ?? null,
            'mood' => $validated['mood'] ?? null,
        ]);

        Auth::guard('store')->login($user);
        $request->session()->regenerate();
        $request->session()->forget('signup');

        return redirect()->route('store.top');
    }
}

