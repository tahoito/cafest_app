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

        $favoriteStores = collect();
        $folderTitle = null;

        // すべてのお気に入り（最新順）
        $favoritesAll = $user->favorites()
            ->with(['latestImage'])
            ->orderByDesc('user_favorites.created_at') // pivot名が違うなら修正
            ->get();

        // フォルダ一覧（フォルダ内店舗も取得）
        $folders = FavoriteFolder::where('user_id', $userId)
            ->with(['stores.latestImage'])
            ->get();

        $folder = $request->query('folder');
        
        if ($folder === 'all') {
            $stores = $user->favorites()
                ->with('latestImage')
                ->orderByDesc('user_favorites.created_at')
                -get();
        } else {
            $folderModel = FavoriteFolder::where('user_id',$userId)
                ->findOrFail($folder);

            $stores = $folderModel->stores()
                ->with('latestImage')
                ->orderByDesc('favorite_folders_store.created_at')
                ->get();
                
            $title = $folderModel->name;
        }

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

        // 左上「すべて」用コラージュ（最大4枚）
        $allThumbs = $favoritesAll
            ->map(fn ($s) => $s->latestImage 
                ? Storage::url($s->latestImage->path)
                : null 
            )
            ->filter()
            ->take(4)
            ->values();

        // お気に入り判定用（StoreCardで使うなら）
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
            'favoriteStores',
            'folderTitle',
        ));
    }

    public function edit(Request $request)
    {
        return view('pages.user.mycafe.edit');
    }
}
