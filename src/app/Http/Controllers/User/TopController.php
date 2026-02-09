<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\StoreRecommendService;
use App\Services\TagRecommendService;
use App\Models\Store;
use App\Models\FavoriteFolder;


class TopController extends Controller
{
    public function index(
        StoreRecommendService $storeService,
        TagRecommendService $tagService
    )
    {
        
        $allStores = Store::query()
            ->with(['latestImage'])
            ->withAvg('reviews', 'rating')
            ->orderByDesc('created_at')
            ->get();

        
        $recommendedStores = $storeService->recommended(4);
        $reviews = Review::with(['user','store','tags'])->latest()->take(6)->get();
        
        $user = auth('user')->user();

        $defaultFolderId = FavoriteFolder::where('user_id', $user->id)
            ->where('name','お気に入り')
            ->value('id');

        $favIds = $defaultFolderId
            ? Store::whereHas('favoriteFolders', function($q) use ($defaultFolderId) {
                $q->where('favorite_folders.id', $defaultFolderId);
            })->pluck('stores.id')->all()
            : [];

        return view('pages.user.top', [
            'recommendedStores' => $recommendedStores,
            'allStores' => $allStores,
            'reviews' => $reviews,
            'recommendedTags' => $tagService->recommended(5),
            'favIds' => $favIds,
        ]);
    }
}
