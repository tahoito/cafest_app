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
        $userId = auth('user')->id();

        $reviews = Review::query()
            ->where('store_id',$store->id)
            ->with([
                'user:id,name,handle,icon_path',
                'store:id,name',
            ])
            ->latest();

        $faved = auth()->check()
            ? auth()->user()->favorites()->where('store_id', $store->id)->exists()
            : false;


        return view('pages.user.stores.reviews', compact('store', 'reviews','faved'));
    }

    public function show(Store $store, Review $review)
    {
        if ($review->store_id !== $store->id) abort(404);

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