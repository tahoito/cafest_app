<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreMenuManegeController extends Controller
{
    public function index() {
        return view('pages.store.menu');
    }
}
