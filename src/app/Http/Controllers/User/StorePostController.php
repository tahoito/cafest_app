<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Services\StoreRecommendService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;



class StoreReviewController extends Controller
{
    public function index($storeId)
    {
        $store = (object) ['id' => $storeId, 'name' => 'ダミー店舗'];

        $posts = collect(range(1, 10))->map(function ($i) {
            return (object) [
                'id' => $i,
                'title' => "おすすめポイント {$i}",
                'caption' => "このメニューが最高だった！ {$i}",
                'image' => '/images/store/image_example.png',
                'likes' => rand(10, 200),
                'created_at' => now()->subDays($i),
                'user' => (object)[
                    'name' => "User {$i}",
                    'avatar' => '/images/user/avatar.png',
                ],
            ];
        });

        return view('user.stores.posts', compact('store', 'posts'));
    }

}
