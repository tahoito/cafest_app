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

        $reviews = collect(range(1, 12))->map(function ($i) {
            return (object) [
                'id' => $i,
                'user' => (object)[
                    'name' => "User {$i}",
                    'avatar' => '/images/user/avatar.png',
                ],
                'rating' => rand(30, 50) / 10, // 3.0〜5.0
                'body' => "雰囲気よかった！作業しやすいしまた行きたい {$i}",
                'created_at' => now()->subDays($i),
                'images' => [
                    '/images/store/image_example.png',
                    '/images/store/image_example.png',
                ],
            ];
        });

        return view('pages.user.stores.reviews', compact('store', 'reviews'));
    }

}