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




class StorePostController extends Controller
{
    public function index($storeId)
    {

        $store = Store::findOrFail($storeId);
        $userId = auth('user')->id();

        $faved = $store->favoriteFolders()
            ->where('favorite_folders.user_id', $userId)
            ->exists();


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
                        'image' => MediaUrl::from($image->path),
                        'created_at' => $review->created_at,
                        'user' => (object)[
                            'name' => $review->user->name,
                            'avatar_url' => MediaUrl::from(data_get($review, 'user.avatar_url')),
                        ],
                    ];
                });
            });
        
        return view('pages.user.stores.posts', compact('store', 'posts','faved'));
    
    }

}
