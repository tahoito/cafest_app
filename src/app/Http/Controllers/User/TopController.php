<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\StoreRecommendService;
use App\Services\TagRecommendService;
use App\Models\Store;

class TopController extends Controller
{
    public function index(
        StoreRecommendService $storeService,
        TagRecommendService $tagService
    )
    {
        
        $allStores = Store::query()
            ->with(['latestImage'])
            ->orderByDesc('created_at')
            ->get();

        
        $recommendedStores = $storeService->recommended(4);
        $reviews = Review::with(['user','store','tags'])->latest()->take(6)->get();
        $favIds = auth()->check()
            ? auth()->user()->favorites->pluck('stores.id')->toArray()
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
