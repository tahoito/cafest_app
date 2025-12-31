<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Services\StoreRecommendService;

class SearchController extends Controller
{
    public function index(StoreRecommendService $service)
    {
        $stores = $service->recommended();
        return view('pages.user.search', compact('stores'));
    }
}
