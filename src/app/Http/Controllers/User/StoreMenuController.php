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

        $faved = auth()->check()
            ? auth()->user()->favorites()->where('store_id', $store->id)->exists()
            : false;

        $menuPhotos = $store->menuPhotos()->take(3)->get();
        $recommendedItems = $store->recommendedItems()->take(3)->get();

        return view('pages.user.stores.menu', compact('store', 'menuPhotos', 'recommendedItems','faved'));
    }


}
