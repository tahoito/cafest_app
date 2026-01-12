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

        $folders = FavoriteFolder::where('user_id',$userId)
            ->orderBy('id','desc')
            ->get(['id','name']);

        $selectedFolderIds = $store->favoriteFolders()
            ->where('user_id',$userId)
            ->pluck('favorite_folder_id')
            ->values();
        
        return response()->json([
            'folders' => $folders,
            'selected_folder_ids' => $selectedFolderIds,
        ]);
    }

    public function sync(Request $request, Store $store)
    {
        $userIds = auth('user')->id();

        $folderIds = $request->input('folder_ids', []);
        if (!is_array($folderIds)) $folderIds = [];

        $validFolderIds = FavoriteFolder::where('user_id', $userId)
            ->whereIn('id', $folderIds)
            ->pluck('id')
            ->all();
        
        $store->favoriteFolders()
            ->where('favorite_folders.user_id', $userId)
            ->detach();
        
        if (count($validFolderIds)) {
            $store->favoriteFolders()->attach($validFolderIds);
        }

        return response()->json(['ok' => true]);
    }
    
}
