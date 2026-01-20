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

        $q = Review::query()
            ->where('store_id', $store->id)
            ->with(['user','images']);

        switch ($filter) {
            case '5':
                $q->where('rating','>=',5.0);
                break;
            case '4':
                $q->where('rating','>=',4.0)
                    ->where('rating','<',5.0);
                break;
            case '3':
                $q->where('rating','<=',3.0);
                break;
            case 'with_photo':
                $q->whereHas('images');
                break;
            case 'no_photo':
                $q->whereDoesntHave('images');
                break;

            default:
                break;
        }

        $reviews = $q->latest()->get();

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
            'avgRating',
            'reviewCount',
            'thisWeekCount',
        ));
    }
}
