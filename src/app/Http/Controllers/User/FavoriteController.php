<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Store $store)
    {
        $userId = auth()->id();

        $favorite = UserFavorite::where('user_id', $userId)
            ->where('store_id', $store->id)
            ->first();

        if ($favorite) {
            // OFF
            $favorite->delete();

            DB::table('favorite_folder_store')
                ->where('store_id', $store->id)
                ->delete();

            return response()->json(['status' => 'removed']);
        } else {
            // ON
            UserFavorite::create([
                'user_id' => $userId,
                'store_id' => $store->id,
            ]);

            return response()->json(['status' => 'added']);
        }
    }
}
