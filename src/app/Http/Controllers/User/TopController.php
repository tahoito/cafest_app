<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\StoreRecommendService;
use App\Services\TagRecommendService;

class TopController extends Controller
{
    public function index(
        StoreRecommendService $storeService,
        TagRecommendService $tagService
    )
    {
        $reviews = Review::with(['user','store','tags'])->latest()->take(6)->get();
        $favIds = auth()->check()
            ? auth()->user()->favorites->pluck('stores.id')->toArray()
            : [];

        return view('pages.user.top', [
            'stores' => $storeService->recommended(4),
            'reviews' => $reviews,
            'recommendedTags' => $tagService->recommended(5),
            'favIds' => $favIds,
        ]);
    }
}
