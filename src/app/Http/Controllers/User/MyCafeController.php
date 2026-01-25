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
            ->with(['stores' => function($q) {
                $q->with('latestImage')
                    ->orderByPivot('created_at', 'desc');
            }])
            ->get();


        $foldersPayload = $folders->map(function ($folder) {
            $stores4 = $folder->stores->take(4);

            $thumbUrls = $stores4 
                ->map(fn($s) => $s->card_image_url ?? null)
                ->filter()
                ->unique()
                ->values()
                ->all();

            return [
                'id' => $folder->id,
                'name' => $folder->name,
                'count' => $folder->stores->count(),
                'thumb_urls' => $thumbUrls,
            ];
        })->values();


        $allThumbs = $favoritesAll
            ->map(fn ($s) => $s->card_image_url ?? null )
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

        if ($request->hasFile('icon_path')){
            if($user->avatar_path){
                Storage::disk('public')->delete($user->icon_path);
            }
            $path = $request->file('icon_path')->store('user_icon', 'public');
            $user->icon_path = $path;
        }

        $user->save();

        return redirect()->route('user.mycafe')->with('success','更新しました');
    }

    public function favoriteFolder($folder)
    {
        $user = Auth::guard('user')->user();
        $userId = $user->id;

        $folderId = null;
        $folderName = "";

        if ($folder === 'all') {
            $stores = $user->favorites()
                ->with('latestImage')
                ->orderByDesc('user_favorites.created_at')
                ->get();
            $title = 'すべての投稿';
        } else {
            $folderModel = FavoriteFolder::where('user_id', $userId)
                ->findOrFail((int) $folder);

            $folderId = $folderModel->id;
            $folderName = $folderModel->name; 

            $stores = $folderModel->stores()
                ->with('latestImage')
                ->orderByDesc('favorite_folders_store.created_at')
                ->get();
            $title = $folderModel->name;
        }

        $favIds = $stores->pluck('id')->all();

        return view('pages.user.mycafe.mycafe_favorites',compact('stores', 'favIds', 'title', 'folder','folderId','folderName'));
    }

    public function destroy(FavoriteFolder $folder)
    {
        $user = Auth::guard('user')->user();
        $userId = $user->id; 

        abort_unless($folder->user_id === $userId, 403);

        $folder->stores()->detach();
        $folder->delete();

        return redirect()->route('user.mycafe');
    }

    public function updateFavoriteFolder(Request $request, FavoriteFolder $folder) {
        $userId = auth('user')->id();

        if ($folder->name === 'お気に入り') abort(403);
        abort_unless($folder->user_id === $userId, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $folder->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'ok' => true,
            'id' => $folder->id,
            'name' => $folder->name,
        ]);
    }
}
