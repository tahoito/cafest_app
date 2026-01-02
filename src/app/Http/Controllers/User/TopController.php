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
        $reviews = Review::with(['user','store'])->latest()->take(6)->get();

        return view('pages.user.top', [
            'stores' => $storeService->recommended(4),
            'reviews' => $reviews,
            'recommendedTags' => $tagService->recommended(5),
        ]);
    }
}
