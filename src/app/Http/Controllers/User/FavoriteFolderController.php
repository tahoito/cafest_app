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
            ->with(['stores.latestImage']) 
            ->get();

            
        $selectedFolderIds = $store->favoriteFolders()
            ->where('favorite_folders.user_id', $userId)
            ->pluck('favorite_folders.id')
            ->values();

            
        $foldersPayload = $folders->map(function ($folder) {
            $latestStore = $folder->stores()
                ->orderByDesc('favorite_folders_store.created_at')
                ->first();

            return [
                'id' => $folder->id,
                'name' => $folder->name,
                'latest_store' => $latestStore ? [
                    'image_url' => $latestStore->card_image_url,
                ] : null,
            ];
        })->values();

        return response()->json([
            'folders' => $foldersPayload,
            'selected_folder_ids' => $selectedFolderIds,
        ]);
    }


    public function sync(Request $request, Store $store)
    {
        $userId = auth('user')->id();

        $folderIds = $request->input('folder_ids', []);
        if (!is_array($folderIds)) $folderIds = [];

       
        $myFolderIds = FavoriteFolder::where('user_id', $userId)
            ->pluck('id')
            ->all();

        $validFolderIds = array_values(array_intersect($myFolderIds, $folderIds));
        
        $store->favoriteFolders()->syncWithoutDetaching($validFolderIds);

        foreach($validFolderIds as $fid) {
            $store->favoriteFolders()->updateExistingPivot($fid, [
                'updated_at' => now(),
            ]);
        }

        $detachIds = array_values(array_diff($myFolderIds, $validFolderIds));
        if (count($detachIds)) {
            $store->favoriteFolders()->detach($detachIds);
        }

        return response()->json(['ok' => true]);
    }

    public function store(Request $request, Store $store) {
        $userId = auth('user')->id();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $folder = FavoriteFolder::create([
            'user_id' => $userId,
            'name' => $validated['name'],
        ]);

        $folder->stores()->syncWithoutDetaching([$store->id]);

        return response()->json([
            'id' => $folder->id,
            'name' => $folder->name,
            'latest_store' => [
                'image_url' => $store->card_image_url,
            ],
            'selected' => true,
        ], 201);
    }

    public function update(Request $request, FavoriteFolder $folder) {
        $userId = auth('user')->id();

        if ($folder->name === 'お気に入り') abort(403);
        
        if ($folder->user_id !== $userId) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $folder->update([
            'name' => $validated['name'],
        ]);

        return back();
    }

}
