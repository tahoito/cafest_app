<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class StoreReviewsController extends Controller
{
    public function index(Request $request) {
        $store = auth('store')->user();

        $reviews = Review::where('store_id', $store->id)
            ->with(['user','images'])
            ->latest()
            ->get();

        return view('pages.store.reviews',compact('store','reviews'));
    }
}
