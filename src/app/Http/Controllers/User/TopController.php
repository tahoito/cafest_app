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
            ->with([
                // カード用に必要な画像だけ（is_used_on_card優先）
                'slideImages' => fn($q) => $q->where('type','slide')->orderBy('sort_order'),
            ])
            ->withAvg('reviews', 'rating')
            ->orderByDesc('created_at')
            ->take(30)
            ->get();

        
        $recommendedStores = $storeService->recommended(4)->load([
            'slideImages' => fn($q) => $q->where('type','slide')->orderBy('sort_order'),
        ])->loadAvg('reviews','rating');

        $reviews = Review::with(['user','store','tags'])->latest()->take(6)->get();
        
        $user = auth('user')->user();

        $defaultFolderId = FavoriteFolder::where('user_id', $user->id)
            ->where('name','お気に入り')
            ->value('id');

        $favIds = [];

        if ($defaultFolderId) {
            $favIds = \DB::table('favorite_folders_store')
                ->where('favorite_folder_id', $defaultFolderId)
                ->pluck('store_id')
                ->all();
        }


        return view('pages.user.top', [
            'recommendedStores' => $recommendedStores,
            'allStores' => $allStores,
            'reviews' => $reviews,
            'recommendedTags' => $tagService->recommended(5),
            'favIds' => $favIds,
        ]);
    }
}
