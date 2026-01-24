<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Review;
use App\Models\ViewHistory;
use App\Models\FavoriteFolder;

class MyCafeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();
        $userId = $user->id;

        $favoritesAll = $user->favorites()
            ->with(['latestImage'])
            ->orderByDesc('user_favorites.created_at')
            ->get();

        $folders = FavoriteFolder::where('user_id', $userId)
            ->with(['stores.latestImage'])
            ->get();


        $foldersPayload = $folders->map(function ($folder) {
            $latestStore = $folder->stores
                ->sortByDesc(fn ($s) => optional($s->pivot)->created_at)
                ->first();

            $imageUrl = ($latestStore && $latestStore->latestImage)
                ? Storage::url($latestStore->latestImage->path)
                : null;

            return [
                'id' => $folder->id,
                'name' => $folder->name,
                'count' => $folder->stores->count(),
                'thumb_url' => $imageUrl,
            ];
        });


        $allThumbs = $favoritesAll
            ->map(fn ($s) => $s->latestImage 
                ? Storage::url($s->latestImage->path)
                : null 
            )
            ->filter()
            ->take(4)
            ->values();

       
        $favIds = $favoritesAll->pluck('id')->all();

        $reviews = Review::where('user_id', $userId)
            ->with('store')
            ->latest()
            ->get();

        $histories = ViewHistory::where('user_id', $userId)
            ->with(['store' => fn ($q) => $q->withAvg('reviews', 'rating')])
            ->orderByDesc('viewed_at')
            ->limit(30)
            ->get();

        return view('pages.user.mycafe', compact(
            'user',
            'favoritesAll',
            'foldersPayload',
            'allThumbs',
            'favIds',
            'reviews',
            'histories',
        ));
    }

    public function edit(Request $request)
    {
        $user = Auth::guard('user')->user();

        return view('pages.user.mycafe.edit',compact('user'));
    }

    public function update(Request $request) 
    {
        $user = Auth::guard('user')->user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:225'],
            'icon_path' => ['nullable', 'image', 'max:4096'],
        ]);

        $user->name = $validated['username'];
        $user->email = $validated['email'];

        if ($request->hasFile('icon')){
            if($user->avatar_path){
                Storage::disk('public')->delete($user->icon_path);
            }
            $path = $request->file('icon')->store('user_icon', 'public');
            $user->icon_path = $path;
        }

        $user->save();

        return redirect()->route('user.mycafe')->with('success','更新しました');
    }

    public function favoriteFolder($folder)
    {
        $user = Auth::guard('user')->user();
        $userId = $user->id;

        if ($folder === 'all') {
            $stores = $user->favorites()
                ->with('latestImage')
                ->orderByDesc('user_favorites.created_at')
                ->get();
            $title = 'すべての投稿';
        } else {
            $folderModel = FavoriteFolder::where('user_id', $userId)
                ->findOrFail((int) $folder);
            $stores = $folderModel->stores()
                ->with('latestImage')
                ->orderByDesc('favorite_folders_store.created_at')
                ->get();
            $title = $folderModel->name;
        }

        $favIds = $stores->pluck('id')->all();

        return view('pages.user.mycafe.mycafe_favorites',compact('stores', 'favIds', 'title', 'folder'));
    }
}
