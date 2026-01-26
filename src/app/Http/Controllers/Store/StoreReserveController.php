<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreReserveController extends Controller
{
    public function index() {
        return view('pages.store.reserve');
    }
}
