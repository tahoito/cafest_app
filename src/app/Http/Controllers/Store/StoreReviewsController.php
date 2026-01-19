<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreReviewsController extends Controller
{
    public function index(Request $request) {
        return view('pages.store.reviews');
    }
}
