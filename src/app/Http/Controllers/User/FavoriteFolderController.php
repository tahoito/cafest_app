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
            $latestStore = $folder->stores 
                ->sortByDesc(fn ($s) => optional($s->pivot)->created_at)
                ->first();

            $imageUrl = null;
            if($latestStore && $latestStore->latestImage) {
                $imageUrl = asset('storage/'.ltrim($latestStore->latestImage->path,'/'));
            }

            return [
                'id' => $folder->id,
                'name' => $folder->name,
                'latest_store' => $latestStore ? [
                    'id' => $latestStore->id,
                    'image_url' => $imageUrl,
                ] : null,
            ];
        });

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

      
        $validFolderIds = FavoriteFolder::where('user_id', $userId)
            ->whereIn('id', $folderIds)
            ->pluck('id')
            ->all();

        $store->favoriteFolders()
            ->wherePivot('user_id', $userId)
            ->detach();

        if (count($validFolderIds)) {
            $attach = collect($validFolderIds)->mapWithKeys(fn($id) => [$id => ['user_id' => $userId]]);
            $store->favoriteFolders()->attach($attach->all());
        }

        return response()->json(['ok' => true]);
    }

    public function store(Request $request) {
        $userId = auth('user')->id();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:30'],
        ]);

        $folder = FavoriteFolder::create([
            'user_id' => $userId,
            'name' => $validated['name'],
        ]);

        return response()->json([
            'id' => $folder->id,
            'name' => $folder->name,
            'latest_store' => null,
        ],201);
    }

}
