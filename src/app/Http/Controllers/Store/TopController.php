<?php 

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;

class TopController extends Controller
{
    public function index()
    {
        $store = auth('store')->user();
        return view('pages.store.top',compact('store'));
    }

}
