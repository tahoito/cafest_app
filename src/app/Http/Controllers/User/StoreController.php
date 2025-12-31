<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Services\StoreRecommendService;

class StoreController extends Controller
{
    public function show($store, StoreRecommendService $service)
    {
        $storeData = $service->recommended()->firstWhere('id', (int)$store);
        abort_if(!$storeData, 404);

        $reviews = Review::with(['user','store'])
        ->where('store_id', (int)$store)
        ->latest()
        ->take(10)
        ->get();

        return view('pages.user.stores.show', [
            'store' => $storeData,
            'reviews' => $reviews, 
        ]);
    }
}
