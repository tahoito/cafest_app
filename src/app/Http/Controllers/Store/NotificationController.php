<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index () {

        $store = auth('store')->user();

        $notifications = $store->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $store->unreadNotifications()->count();


        return view('pages.store.notifications',compact('notifications','unreadCount'));
    }

    public function read(String $id, Request $request) {
        $store = auth('store')->user();

        $n = $store->notifications()->where('id',$id)->firstOrFail();
        $n->markAsRead();

        if ($request->expectsJson()) return response()->json(['ok' => true]);

        $url = data_get($n->data, 'url');
        return $url ? redirect($url) : back();
    }


    public function readAll (Request $request) {
        $store = auth('store')->user();

        $store->unreadNotifications->markAsRead();

        if ($request->expectsJson()) return response()->json(['ok'=> true]);
    }
}
