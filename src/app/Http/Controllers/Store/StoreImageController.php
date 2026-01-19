<?php

namespace App\Http\Controllers\Store;

use App\Models\StoreImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StoreImageController extends Controller
{
    public function index(Request $request) {
        $store = auth('store')->user();

        $store->load([
            'slideImages',
            'galleryImages',
        ]);
        
        return view('pages.store.image', compact('store'));
    }

    public function setCardImage(StoreImage $image) {
        $storeId = auth('store')->id();

        abort_unless($image->store_id === $storeId, 403);

        DB::transaction(function () use ($storeId, $image) 
        {
            StoreImage::where('store_id', $storeId)
                ->where('type', 'slide')
                ->update(['is_used_on_card' => false]);

            $image->update(['is_used_on_card' => true]);
        });

        return back();
    }
}
