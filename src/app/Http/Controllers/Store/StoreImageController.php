<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreImageController extends Controller
{
    public function index(Request $request) {
        return view('pages.store.image');
    }
}
