<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Services\StoreRecommendService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;



class StoreController extends Controller
{
    public function show(Store $store, StoreRecommendService $service)
    {
        $store = Store::query()
            ->with(['hours', 'reviews'])
            ->withAvg('reviews','rating')
            ->findOrFail($store->id);

        $reviews = Review::with(['user','store'])
            ->where('store_id', $store->id)
            ->latest()
            ->take(10)
            ->get();

        $reviewCount = Review::query()
            ->where('store_id', $store->id)
            ->count();

        $posts = ReviewImage::query()
            ->whereHas('review', fn($q) => $q->where('store_id',$store->id))
            ->orderByDesc('id')
            ->take(3)
            ->get()
            ->map(fn($img) => (object)[
                'review_id' => $img->review_id,
                'image' => asset('storage/'.$img->path),
            ]);
        
        $store->load(['slideImages','galleryImages','hours','reviews'])
            ->loadAvg('reviews','rating')
            ->findOrFail($store->id);

        $faved = auth()->check()
            ? auth()->user()->favorites()->where('store_id', $store->id)->exists()
            : false;
        
        return view('pages.user.stores.show', [
            'store' => $store,
            'reviews' => $reviews,
            'posts' => $posts,
            'faved' => $faved,
            'reviewCount' => $reviewCount,
        ]);
    }

    public function reserveConfirm(Store $store)
    {
        $data = session("reserve.{$store->id}");

        abort_if(!$data, 419);

        return view('pages.user.reserve-confirm', [
            'store' => $store,
            'data' => $data,
        ]);
    }


    public function reserveConfirmStore(Store $store, Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'people' => ['required', 'integer', 'min:1'],
        ]);

        session(["reserve.{$store->id}" => $data]);

        return redirect()->route('user.stores.reserve.confirm', $store);
    }



    public function reserveStore(Request $request, Store $store)
    {
        $reserveData = session("reserve.{$store->id}");
        abort_if(!$reserveData, 419);

        $validated = $request->validate([
            'name' => ['required','string','max:50'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $phone = preg_replace('/[^0-9]/', '', $validated['phone']);
        $startAt = Carbon::parse($reserveData['date'].' '.$reserveData['start_time']);
        $endAt   = Carbon::parse($reserveData['date'].' '.$reserveData['end_time']);
        $partySize = (int) $reserveData['people'];

        $exits = Reservation::where('store_id', $store->id)
            ->where('status', '!=', 'canceled')
            ->where('start_at', '<', $endAt)
            ->where('end_at', '>', $startAt)
            ->exists();

        if ($exits){
            return back()->withErrors([
                'time' => 'その時間はすでに予約が入っています。'
            ]);
        }

        Reservation::create([
            'store_id' => $store->id,
            'user_id' => auth('user')->id(),
            'name' => $validated['name'],
            'phone' => $phone,
            'start_at' => $startAt,
            'end_at' => $endAt,
            'party_size' => $partySize,
            'status' => 'confirmed',
        ]);

        session()->forget("reserve.{$store->id}");

        return redirect()
            ->route('user.stores.show', $store->id)
            ->with('reserve_success', '予約完了！');
    }

}

