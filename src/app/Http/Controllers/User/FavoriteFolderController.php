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
            ->with(['stores' => function ($q) {
                $q->orderByDesc('favorite_folders_store.created_at');
            }])
            ->get();

        $selectedFolderIds = $store->favoriteFolders()
            ->where('favorite_folders.user_id', $userId)
            ->pluck('favorite_folders.id')
            ->values();

        $foldersPayload = $folders->map(function ($folder) {
            $stores = $folder->stores->take(4);

            $thumbUrls = $stores
                ->map(fn($s) => $s->card_image_url)
                ->filter()
                ->unique()
                ->values()
                ->all();

            return [
                'id' => $folder->id,
                'name' => $folder->name,
                'thumb_urls' => $thumbUrls,
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

}
