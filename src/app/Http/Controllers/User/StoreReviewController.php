<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Services\StoreRecommendService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;



class StoreReviewController extends Controller
{
    public function index(Store $store)
    {
        $storeId = $store->id;
        
        $reviews = collect(range(1, 12))->map(function ($i) {
            return (object) [
                'id' => $i,
                'user' => (object)[
                    'name' => "User {$i}",
                    'avatar' => '/images/user/avatar.png',
                ],
                'store_id' => $storeId,
                'store' => (object)['id' => $storeId, 'name' => 'ダミー店舗'],
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

    public function show(Store $store, Review $review)
    {
        if ($review->store_id !== $store->id) abort(404);

        if (request()->query('format') === 'json') {

            // tags
            $tags = [];
            try {
                if (method_exists($review, 'tags')) {
                    $tags = $review->tags()->pluck('name')->values();
                }
            } catch (\Throwable $e) {
                $tags = [];
            }

            // images
            $images = [];
            try {
                if (method_exists($review, 'images')) {
                    $images = $review->images()
                        ->orderBy('sort')
                        ->pluck('path')
                        ->map(fn ($p) => asset('storage/' . ltrim($p, '/')))
                        ->values();
                }
            } catch (\Throwable $e) {
                $images = [];
            }


            return response()->json([
                'id' => $review->id,
                'store' => ['name' => $store->name],
                'user' => [
                    'name' => data_get($review, 'user.name'),
                    'handle' => data_get($review, 'user.handle'),
                    'avatar_url' => data_get($review, 'user.icon_path')
                        ? asset($review->user->icon_path)
                        : null,
                ],
                'created_at' => optional($review->created_at)->format('Y/m/d'),
                'rating' => (float) $review->rating,
                'body' => (string) ($review->body ?? ''),
                'tags' => $tags,
                'images' => $images,
            ]);
        }

        return view('pages.user.stores.reviews.show', compact('store','review'));
    }


}