<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Services\StoreRecommendService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;



class StorePostController extends Controller
{
    public function index($storeId)
    {
        $userId = auth('user')->id();

        $faved = auth()->check()
            ? auth()->user()->favorites()->where('store_id', $store->id)->exists()
            : false;

        $store = Store::findOrFail($storeId);

        $posts = Review::with(['user','images'])
            ->where('store_id',$storeId)
            ->whereHas('images')
            ->latest()
            ->get()
            ->flatMap(function ($review) {
                return $review->images->map(function ($image) use ($review){
                    return (object) [
                        'review_id' => $review->id,
                        'image_id' => $image->id,
                        'image' => asset('storage/' . $image->path),
                        'created_at' => $review->created_at,
                        'user' => (object)[
                            'name' => $review->user->name,
                            'avatar' => $review->user->avatar_url,
                        ],
                    ];
                });
            });
        
        return view('pages.user.stores.posts', compact('store', 'posts'));
    
    }

}
