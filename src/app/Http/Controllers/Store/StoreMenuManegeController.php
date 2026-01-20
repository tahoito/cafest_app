<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuPhoto;
use Illuminate\Support\Facades\Storage;


class StoreMenuManegeController extends Controller
{
    public function index() 
    {
        $store = auth('store')->user();

        $menuPhotos = $store->menuPhotos;
        $recommendedItems = $store->recommendedItems()->orderBy('sort_order')->take(3)->get();

        return view('pages.store.menu', compact('store','menuPhotos','recommendedItems'));
    }

    public function editImage (Request $request) {

        $store = auth('store')->user();
        $menuPhotos = $store->menuPhotos()
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('pages.store.menu.edit-image', compact('store','menuPhotos'));
    }

    public function uploadImage(Request $request) {
        $store = auth('store')->user();

        if (!$store) abort(403);

        $request->validate([
            'photos' => ['required', 'array'],
            'photos.*' => ['image','max:5120'],
        ]);

        $currentCount = $store->menuPhotos()->count();
        $canAdd = max(0, 3 - $currentCount);

        if ($canAdd <= 0) {
            return back()->with('error', '画像は3枚までです');
        }

        $files = array_slice($request->file('photos'), 0, $canAdd);

        foreach ($files as $file) {
            $path = $file->store('menu_photos', 'public');

            $store->menuPhotos()->create([
                'photo_path' => $path,
                'sort_order' => ($store->menuPhotos()->max('sort_order') ?? 0) + 1,
            ]);
        }

        return back()->with('success', '画像を追加しました');

    }

    public function deleteImage(Request $request) {
        $store = auth('store')->user();
        if (!$store) abort(403);

        $photoId = $request->input('menu_photo_id');

        $photo = MenuPhoto::where('id', $photoId)
            ->where('store_id', $store->id)
            ->firstOrFail();

        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();

        return back()->with('success','画像を削除しました');
    }

    public function updateImage(Request $request) {
        $store = auth('store')->user();
        if(!$store) abort(403);

        $ids = $request->input('menu_photo_ids', []);
        $ids = array_slice($ids,0,3);

        foreach($ids as $index => $id) {
            MenuPhoto::where('id',$id) 
                ->where('store_id', $store->id)
                ->update(['sort_order' => $index + 1]);
        }

        return redirect()
            ->route('store.menu')
            ->with('success','保存しました');        
    }


}
