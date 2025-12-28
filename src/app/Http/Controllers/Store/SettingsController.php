<?php 

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;
use App\Http\Requests\Store\SettingsRequest;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('signup')) {
        return redirect()->route('store.signup');
        }
        return view('pages.store.settings');
    }

    public function store(SettingsRequest $request)
    {
        if (!$request->session()->has('signup')) {
            return redirect()->route('store.signup');
        }

        $signup = $request->session()->get('signup');
        $validated = $request->validated();

        $areas = json_decode($validated['area'], true);
        $moods = json_decode($validated['mood'], true);


        $store = Store::create([
            'name' => $validated['name'],
            'email' => $signup['email'],
            'password' => Hash::make($signup['password']),
            'address' => $validated['address'],
            'area' => $areas,
            'mood' => $moods,
        ]);

        Auth::guard('store')->login($store);
        $request->session()->regenerate();
        $request->session()->forget('signup');

        return redirect()->route('store.top');
    }
}

