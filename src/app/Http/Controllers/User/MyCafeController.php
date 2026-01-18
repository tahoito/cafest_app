<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyCafeController extends Controller
{
    public function index(Request $request) {
        return view('pages.user.mycafe');
    }
}
