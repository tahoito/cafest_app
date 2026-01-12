<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Store $store)
    {
        $user = auth()->user();

        if ($user->favorites()->where('store_id', $store->id)->exists()) {
            $user->favorites()->detach($store->id);
            return response()->json(['favorited' => false]);
        }

        $user->favorites()->attach($store->id);
        return response()->json(['favorited' => true]);
    }
}
