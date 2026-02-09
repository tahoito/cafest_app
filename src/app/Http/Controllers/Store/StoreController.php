<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $store = auth('store')->user();
        $unreadCount = $store->unreadNotifications()
            ->where('data->type', '!=', 'reservation.visited')
            ->count();

        return view('pages.store.top', compact('store', 'unreadCount'));
    }

    
    public function togglePublic(Request $request)
    {
        $request->validate([
            'is_public' => ['required'],
        ]);

        $store = auth('store')->user();
        if (!$store) abort(401);

        $store->is_public = $request->boolean('is_public');
        $store->save();

        return response()->json([
            'success' => true,
            'is_public' => $store->is_public,
        ]);
    }

}
