<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

class StoreMenuController extends Controller
{
    public function show(Store $store)
    {
        $userId = auth('user')->id();

        $faved = \DB::table('user_favorites')
            ->where('user_id', $userId)
            ->where('store_id', $store->id)
            ->exists();

        $menuPhotos = $store->menuPhotos()->take(3)->get();
        $recommendedItems = $store->recommendedItems()->take(3)->get();

        return view('pages.user.stores.menu', compact('store', 'menuPhotos', 'recommendedItems'));
    }


}
