<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\StoreImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StoreImageController extends Controller
{
    public function index(Request $request)
    {
        $store = auth('store')->user();

        $store->load([
            'slideImages',
            'galleryImages',
        ]);

        return view('pages.store.image', compact('store'));
    }

    public function setCardImage(StoreImage $image)
    {
        $storeId = auth('store')->id();

        abort_unless($image->store_id === $storeId, 403);
        abort_unless($image->type === 'slide', 403);

        DB::transaction(function () use ($storeId, $image) {
            StoreImage::where('store_id', $storeId)
                ->where('type', 'slide')
                ->update(['is_used_on_card' => false]);

            $image->update(['is_used_on_card' => true]);
        });

        return back()->with('ok', 'updated');
    }

    public function editSlide(Request $request)
    {
        $store = $request->user('store');
        $store->load(['slideImages']);

        return view('pages.store.image.edit-slide', compact('store'));
    }

    public function uploadSlide(Request $request)
    {
        $storeId = auth('store')->id();

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ]);

        $count = StoreImage::where('store_id', $storeId)
            ->where('type', 'slide')
            ->count();

        abort_if($count >= 5, 403);

        $nextSort = (int) (StoreImage::where('store_id', $storeId)
            ->where('type', 'slide')
            ->max('sort_order') ?? 0) + 1;

        $path = $request->file('image')->store("stores/{$storeId}/slides", 'public');

        $img = StoreImage::create([
            'store_id' => $storeId,
            'type' => 'slide',
            'sort_order' => $nextSort,
            'is_used_on_card' => false,
            'path' => "storage/{$path}",
        ]);

        $hasCard = StoreImage::where('store_id', $storeId)
            ->where('type', 'slide')
            ->where('is_used_on_card', true)
            ->exists();

        if (! $hasCard) {
            $img->update(['is_used_on_card' => true]);
        }

        return back()->with('ok', 'uploaded');
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

            if (is_string($image->path) && str_starts_with($image->path, 'storage/')) {
                $relative = substr($image->path, strlen('storage/'));
                Storage::disk('public')->delete($relative);
            }

            $image->delete();

            if ($wasCard) {
                $next = StoreImage::where('store_id', $storeId)
                    ->where('type', 'slide')
                    ->orderBy('sort_order')
                    ->first();

                if ($next) {
                    StoreImage::where('store_id', $storeId)
                        ->where('type', 'slide')
                        ->update(['is_used_on_card' => false]);

                    $next->update(['is_used_on_card' => true]);
                }
            }
        });

        return back()->with('ok', 'deleted');
    }


    public function updateSlide(Request $request)
    {
        $storeId = auth('store')->id();
        $store = auth('store')->user();

        $request->validate([
            'image_ids' => ['nullable', 'array'],
            'image_ids.*' => ['integer'],
        ]);

        $ids = collect($request->input('image_ids', []))->filter()->values();

        DB::transaction(function () use ($storeId, &$ids) {

            $images = StoreImage::where('store_id', $storeId)
                ->where('type', 'slide')
                ->orderBy('sort_order')
                ->get();

            if ($ids->isEmpty()) {
                $ids = $images->pluck('id')->values();
            } else {
                $ids = $ids->intersect($images->pluck('id'))->values();
            }

            $ids = $ids->take(5)->values();

            foreach ($ids as $i => $id) {
                StoreImage::where('id', $id)->update([
                    'sort_order' => $i + 1,
                ]);
            }

            $hasCard = StoreImage::where('store_id', $storeId)
                ->where('type', 'slide')
                ->where('is_used_on_card', true)
                ->exists();

            if (! $hasCard && $ids->isNotEmpty()) {
                StoreImage::where('store_id', $storeId)
                    ->where('type', 'slide')
                    ->update(['is_used_on_card' => false]);

                StoreImage::where('id', $ids->first())
                    ->update(['is_used_on_card' => true]);
            }
        });

        $store->update([
            'slide_updated_at' => now(),
        ]);
        return redirect()->route('store.image')->with('ok', 'saved');
    }

    public function editGallery(Request $request) {
        $store = $request->user('store');
        $store->load(['galleryImages']);

        return view('pages.store.image.edit-gallery', compact('store'));
    }

    public function uploadGallery(Request $request)
    {
        $storeId = auth('store')->id();

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ]);

        $count = StoreImage::where('store_id', $storeId)
            ->where('type', 'gallery')
            ->count();

        abort_if($count >= 6, 403);

        $nextSort = (int) (StoreImage::where('store_id', $storeId)
            ->where('type', 'gallery')
            ->max('sort_order') ?? 0) + 1;

        $path = $request->file('image')->store("stores/{$storeId}/galleries", 'public');

        StoreImage::create([
            'store_id' => $storeId,
            'type' => 'gallery',
            'sort_order' => $nextSort,
            'path' => "storage/{$path}",
        ]);

        return back()->with('ok', 'uploaded');
    }

    public function deleteGallery(Request $request)
    {
        $storeId = auth('store')->id();

        $request->validate([
            'image_id' => ['required', 'integer'],
        ]);

        DB::transaction(function () use ($storeId, $request) {
            $image = StoreImage::where('id', $request->image_id)
                ->where('store_id', $storeId)
                ->where('type', 'gallery')
                ->firstOrFail();


            if (is_string($image->path) && str_starts_with($image->path, 'storage/')) {
                $relative = substr($image->path, strlen('storage/'));
                Storage::disk('public')->delete($relative);
            }

            $image->delete();
        });

        return back()->with('ok', 'deleted');
    }

    public function updateGallery(Request $request)
    {
        $storeId = auth('store')->id();
        $store = auth('store')->user();

        $request->validate([
            'image_ids' => ['nullable', 'array'],
            'image_ids.*' => ['integer'],
        ]);

        $ids = collect($request->input('image_ids', []))->filter()->values();

        DB::transaction(function () use ($storeId, &$ids) {

            $images = StoreImage::where('store_id', $storeId)
                ->where('type', 'gallery')
                ->orderBy('sort_order')
                ->get();

            if ($ids->isEmpty()) {
                $ids = $images->pluck('id')->values();
            } else {
                $ids = $ids->intersect($images->pluck('id'))->values();
            }

            $ids = $ids->take(6)->values();

            foreach ($ids as $i => $id) {
                StoreImage::where('id', $id)->update([
                    'sort_order' => $i + 1,
                ]);
            }
        });

        $store->update([
            'gallery_updated_at' => now()
        ]);
        
        return redirect()->route('store.image')->with('ok', 'saved');
    }
}
