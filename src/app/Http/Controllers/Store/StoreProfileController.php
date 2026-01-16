<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreProfileController extends Controller
{
    public function index() {
        return view('pages.store.profile');
    }
    
}
