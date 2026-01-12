<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\UserFavorite;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function toggle(Store $store)
    {
        $userId = auth('user')->id(); // userガード

        $favorite = UserFavorite::where('user_id', $userId)
            ->where('store_id', $store->id)
            ->first();

        if ($favorite) {
            // OFF
            $favorite->delete();

            // フォルダpivotからも削除（※そのユーザーのフォルダだけに絞るなら後で改善）
            DB::table('favorite_folder_store')
                ->where('store_id', $store->id)
                ->delete();

            return response()->json(['status' => 'removed']);
        }

        // ON
        UserFavorite::create([
            'user_id' => $userId,
            'store_id' => $store->id,
        ]);

        return response()->json(['status' => 'added']);
    }
}
