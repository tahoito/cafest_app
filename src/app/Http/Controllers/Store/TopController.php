<?php 

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;

class TopController extends Controller
{
    public function index()
    {
        return view('pages.store.top');
    }
}
