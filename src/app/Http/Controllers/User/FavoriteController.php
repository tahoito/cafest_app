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

        $default = \App\Models\FavoriteFolder::firstOrCreate(
            ['user_id' => $userId, 'name' => 'お気に入り']
        );

        $exists = $store->favoriteFolders()
            ->wherePivot('user_id', $userId)
            ->where('favorite_folders.id', $default->id)
            ->exists();

        if ($exists) {
            $store->favoriteFolders()
                ->wherePivot('user_id', $userId)
                ->detach();

            return response()->json(['status' => 'removed']);
        }

        $store->favoriteFolders()->attach([
            $default->id => ['user_id' => $userId]
        ]);

        return response()->json(['status' => 'added']);
    }
}
