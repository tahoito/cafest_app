<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Services\StoreRecommendService;
use Illuminate\Http\Request;


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

    public function reserveConfirm(Store $store, Request $request)
    {
        $data = $request->only(['date', 'start_time', 'end_time', 'people']);

        return view('pages.user.reserve-confirm', [
            'store' => $store,
            'data' => $data,
        ]);
    }
    public function reserveStore(Request $request, Store $store)
    {
        return redirect()
            ->route('user.stores.show', $store)
            ->with('success', '予約完了！');
    }

}

