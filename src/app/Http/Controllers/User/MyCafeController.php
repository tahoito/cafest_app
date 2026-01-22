<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\Review;

class MyCafeController extends Controller
{
    public function index(Request $request) {

        $user = Auth::guard('user')->user();

        $favorites = $user->favorites()->get();

        $reviews = Review::where('user_id', $user->id)
            ->with('store')
            ->latest()
            ->get();
        
        $histories = $user->viewHistories()
            ->with('stores')
            ->latest()
            ->get();

        return view('pages.user.mycafe',compact(
            'user',
            'favorites',
            'reviews',
            'histories',
        ));

    }

    public function edit(Request $request) {
        return view('pages.user.mycafe.edit');
    }
}
