<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MyCafeController extends Controller
{
    public function index(Request $request) {

        $user = Auth::guard('user')->user();
        return view('pages.user.mycafe',compact('user'));
    }

    public function edit(Request $request) {
        return view('pages.user.mycafe.edit');
    }
}
