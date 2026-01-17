<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreProfileController extends Controller
{
    public function index() {
        $store = auth('store')->user();

        $store->load([
            'paymentMethods',
            'hours' => fn($q) => $q->orderBy('day_of_week'),
        ]);
        

        return view('pages.store.profile',compact('store'));
    }
    
}
