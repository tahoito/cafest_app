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

        \Log::debug('setCardImage', [
            'login_store_id' => $storeId,
            'image_id' => $image->id,
            'image_store_id' => $image->store_id,
            'type' => $image->type,
        ]);


        abort_unless($image->store_id === $storeId, 403);
        abort_unless($image->type === 'slide', 403);

        DB::transaction(function () use ($storeId, $image) 
        {
            StoreImage::where('store_id', $storeId)
                ->where('type', 'slide')
                ->update(['is_used_on_card' => false]);

            $image->update(['is_used_on_card' => true]);
        });

        return back()->with('ok', 'updated');
    }

    public function editSlide (Request $request) {
        $store = $request->user('store');
        $store->load(['slideImages']);

        return view('pages.store.image.edit-slide', compact('store'));
    }

    public function updateSlide (Request $request) {
        $store = $request->user('store');

        return redirect()->route('store.image')->with('status', 'スライド写真を更新しました');
    }

    public function deleteSlide(Request $request) 
    {
        $storeId = auth('store')->id();

        $request->validate([
            'image_id' => ['required', 'integer'],
        ]);

        DB::transaction(function () use ($storeId, $request) {
            $image = StoreImage::where('id', $request->image_id)
                ->where('store_id', $storeId)
                ->where('type', 'slide')
                ->firstOrFail();

            $wasCard = (bool) $image->is_used_on_card;

            $image->delete();

            if ($wasCard) {
                $next = StoreImage::where('store_id', $storeId)
                    ->where('type', 'slide')
                    ->orderBy('sort_order')
                    ->first();

                if ($next) {
                    $next->update(['is_used_on_card' => true]);
                }
            }
        });

        return back()->with('ok', 'deleted');
    }
}
