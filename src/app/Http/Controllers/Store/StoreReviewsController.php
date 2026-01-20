<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Carbon\Carbon;

class StoreReviewsController extends Controller
{
    public function index(Request $request) {

        $store = auth('store')->user();
        $filter = (string) $request->query('filter','all');
        $sort = (string)  $request->query('sort','new');

        $q = Review::query()
            ->where('store_id', $store->id)
            ->with(['user','images']);

        if ($filter === '5') {
            $q->where('rating', '=', 5);
        } elseif ($filter === '4') {
            $q->where('rating', '>=', 4)->where('rating', '<', 5);
        } elseif ($filter === '3') {
            $q->where('rating', '<=', 3);
        } elseif ($filter === 'with_photo') {
            $q->whereHas('images');
        } elseif ($filter === 'no_photo') {
            $q->whereDoesntHave('images');
        }

        if ($sort === 'old'){
        $q->oldest();
        } elseif ($sort === 'high') {
        $q->orderBy('rating', 'desc')->orderBy('created_at', 'desc');
        } elseif ($sort === 'low') {
        $q->orderBy('rating', 'asc')->orderBy('created_at', 'desc');
        } else {
        $q->orderBy('created_at', 'desc');
        }

        $reviews = $q->get();

        $base = Review::where('store_id', $store->id);

        $avgRating = round((float) $base->avg('rating'), 1);
        $reviewCount = (int) $base->count();

        $thisWeekCount = (int) Review::where('store_id', $store->id)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();

        return view('pages.store.reviews',compact(
            'store',
            'reviews',
            'filter',
            'sort',
            'avgRating',
            'reviewCount',
            'thisWeekCount',
        ));
    }

    public function show(Review $review) {
        $store = auth('store')->user();
        abort_unless($review->store_id === $store->id, 404);

        $review->load(['user', 'images']);

        return view('pages.store.reviews.show', compact('store','review'));
    }
}
