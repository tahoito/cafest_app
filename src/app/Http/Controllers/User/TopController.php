<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\StoreRecommendService;

class TopController extends Controller
{
    public function index(StoreRecommendService $service){
        $reviews = Review::with(['user','store'])->latest()->take(6)->get();
        $stores = $service->recommended(limit:4);

        return view('pages.user.top',compact('stores','reviews'));
    }
}
