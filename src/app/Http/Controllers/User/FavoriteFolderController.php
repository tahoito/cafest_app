<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\FavoriteFolder;
use Illuminate\Http\Request;

class FavoriteFolderController extends Controller
{
    public function index(Store $store)
    {
        $userId = auth('user')->id();

        $folders = FavoriteFolder::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get(['id', 'name']);

        $selectedFolderIds = $store->favoriteFolders()
            ->where('favorite_folders.user_id', $userId)
            ->pluck('favorite_folders.id')
            ->values();

        return response()->json([
            'folders' => $folders,
            'selected_folder_ids' => $selectedFolderIds,
        ]);
    }

    public function sync(Request $request, Store $store)
    {
        $userId = auth('user')->id();

        $folderIds = $request->input('folder_ids', []);
        if (!is_array($folderIds)) $folderIds = [];

      
        $validFolderIds = FavoriteFolder::where('user_id', $userId)
            ->whereIn('id', $folderIds)
            ->pluck('id')
            ->all();

        $myFolderIds = FavoriteFolder::where('user_id', $userId)->pluck('id')->all();
        if (count($myFolderIds)) {
            $store->favoriteFolders()->detach($myFolderIds);
        }

        if (count($validFolderIds)) {
            $store->favoriteFolders()->attach($validFolderIds);
        }

        return response()->json(['ok' => true]);
    }
}
