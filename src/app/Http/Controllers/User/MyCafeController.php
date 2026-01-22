<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\Review;
use App\Models\VIewHistory;

class MyCafeController extends Controller
{
    public function index(Request $request) {


        $user = Auth::guard('user')->user();

        $store = Store::findOrFail($storeId);
        $favorites = $user->favorites()->get();

        $faved = auth()->check()
            ? auth()->user()->favorites()->where('store_id', $store->id)->exists()
            : false;


        $reviews = Review::where('user_id', $user->id)
            ->with('store')
            ->latest()
            ->get();
        
        $histories = ViewHistory::where('user_id', $user->id)
            ->with(['store' => fn($q) => $q->withAvg('reviews','rating')])
            ->orderByDesc('viewed_at')
            ->limit(30)
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
