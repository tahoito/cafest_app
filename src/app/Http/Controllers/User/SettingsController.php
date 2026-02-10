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

        $areas = $validated['area'] ? json_decode($validated['area'], true) : [];
        $moods = $validated['mood'] ? json_decode($validated['mood'], true) : [];


        $user = User::create([
            'name' => $validated['name'],
            'email' => $signup['email'],
            'password' => Hash::make($signup['password']),
            'favorite_areas' => $areas,
            'favorite_moods' => $moods,
            'icon_path' => null,
        ]);

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');

            $ext = $file->getClientOriginalExtension();
            $ext = in_array(strtolower($ext), ['jpg','jpeg','png','webp']) ? strtolower($ext) : 'jpg';

            $filename = 'user_'.$user->id.'.'.$ext;

            $dir = public_path('images/users');
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $file->move($dir, $filename);

            $user->icon_path = '/images/users/'.$filename;
            $user->save();
        }

        Auth::guard('user')->login($user);
        $request->session()->regenerate();
        $request->session()->forget('signup');

        return redirect()->route('user.top');
    }
}
