<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Store;

class RecommendController extends Controller{

    public function recommended()
    {
    $stores = Store::query()
        ->withAvg('reviews as rating', 'rating')
        ->orderByDesc('rating')
        ->take(50)
        ->get();

    return view('pages.user.recommended', compact('stores'));
    }
}

?>