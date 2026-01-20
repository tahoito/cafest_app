<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuPhoto;

class StoreMenuManegeController extends Controller
{
    public function index() 
    {
        $store = auth('store')->user();

        $menuPhotos = $store->menuPhotos;
        $recommendedItems = $store->recommendedItems()->orderBy('sort_order')->take(3)->get();

        return view('pages.store.menu', compact('store','menuPhotos','recommendedItems'));
    }
}
