<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreProfileController extends Controller
{
    public function index() {
        $store = auth('store')->user();

        if (!$store) {
            abort(403);
        }

        $store->load([
            'paymentMethods',
            'hours' => fn($q) => $q->orderBy('day_of_week'),
        ]);
        return view('pages.store.profile',compact('store'));
    }

    public function editBasic (Request $request) {
        $store = $request->user('store')->load(['hours','paymentMethods']);
        return view('pages.store.profile.edit-basic', compact('store'));
    }

    public function updateBasic (Request $request) {
        return redirect()->route('store.profile')->with('status', '基本情報を更新しました');
    }

    public function editDescription (Request $request) {
        $store = $request->user('store');
        return view('pages.store.profile.edit-description', compact('store'));
    }

    public function updateDescription (Request $request) {
        return redirect()->route('store.profile')->with('status', '店舗紹介を更新しました');
    }

    public function editContact (Request $request) {
        $store = $request->user('store');
        return view('pages.store.profile.edit-contact', compact('store'));
    }

    public function updateContact (Request $request) {
        return redirect()->route('store.profile')->with('status', '連絡情報を更新しました');
    }
    
}
