<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;

class TopController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user','store'])
            ->latest()
            ->take(6)
            ->get();

        return view('pages.user.top',compact('reviews'));
    }
}
