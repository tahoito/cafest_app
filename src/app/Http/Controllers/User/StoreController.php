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

    public function reserveConfirm(\Illuminate\Http\Request $request, \App\Models\Store $store)
    {
        // モーダルから来た値をそのまま確認画面に渡す
        $data = $request->only(['date', 'time', 'people', 'note']);

        return view('pages.user.reserve-confirm', compact('store', 'data'));
    }

    public function reserveStore(\Illuminate\Http\Request $request, \App\Models\Store $store)
    {
        // ここは後でDB保存（今は仮でOK）
        // $request->validate([...]);

        return redirect()->route('user.stores.show', $store)->with('success', '予約完了！');
    }

}

