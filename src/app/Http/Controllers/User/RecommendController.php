<?php

namespace App\Http\Controllers\User;
use App\Services\StoreRecommendService; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Store;

class RecommendController extends Controller{

    public function recommended(StoreRecommendService $service)
    {
        $stores = $service->recommended(limit:8);
        
        $favIds = auth('user')->check()
            ? auth('user')->user()->favorites()->pluck('stores.id')->all()
            : [];


        return view('pages.user.recommended', compact('stores','favIds'));
    }
}

?>