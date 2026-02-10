<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\FavoriteFolder;

class FavoriteController extends Controller
{
    public function toggle(Store $store)
    {
        $userId = auth('user')->id(); // userガード

        $default = FavoriteFolder::firstOrCreate(
            ['user_id' => $userId, 'name' => 'お気に入り'],
            []
        );

        $exists = $store->favoriteFolders()
            ->where('favorite_folders.user_id', $userId)
            ->where('favorite_folders.id', $default->id)
            ->exists();

        if ($exists) {
            $store->favoriteFolders()
                ->detach($default->id);

            return response()->json(['status' => 'removed']);
        }

        $store->favoriteFolders()->syncWithoutDetaching([$default->id]);

        return response()->json(['status' => 'added']);
    }
}
