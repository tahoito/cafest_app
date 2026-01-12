<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
     public function index()
    {
        $store = auth('store')->user();
        return view('pages.store.top', compact('store'));
    }

    
    public function togglePublic(Request $request)
    {
        $request->validate([
            'is_public' => 'required|boolean',
        ]);

        $store = auth('store')->user();
        $store->is_public = $request->is_public;
        $store->save();

        return response()->json([
            'success' => true,
            'is_public' => $store->is_public,
        ]);
    }

}
