<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Services\StoreRecommendService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Support\MediaUrl;



class StoreReviewController extends Controller
{
    public function index(Store $store)
    {
        $storeId = $store->id;
        $userId = auth('user')->id();

        $reviews = Review::with(['user','store'])
            ->where('store_id',$store->id)
            ->latest()
            ->get();

        $faved = $store->favoriteFolders()
            ->where('favorite_folders.user_id', $userId)
            ->exists();


        return view('pages.user.stores.reviews', compact('store', 'reviews','faved'));
    }

    public function show(Store $store, Review $review, Request $request )
    {
        if ($review->store_id !== $store->id) abort(404);
        $review->load(['user:id,name,icon_path,handle', 'store:id,name']);
        

        if (request()->query('format') === 'json') {

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
                            ->map(function ($p) {
                                $p = (string) $p;
                                return MediaUrl::from($p);
                            })
                            ->values();
                    }
                } catch (\Throwable $e) {
                    $images = [];
            }

            $iconPath = data_get($review, 'user.icon_path');
            $avatarUrl = MediaUrl::from($iconPath);

            return response()->json([
                'id' => $review->id,
                'store' => ['id' => $store->id, 'name' => $store->name],
                'user' => [
                    'name' => data_get($review, 'user.name'),
                    'handle' => data_get($review, 'user.handle'),
                    'avatar_url' => MediaUrl::from(data_get($review, 'user.icon_path')),
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
