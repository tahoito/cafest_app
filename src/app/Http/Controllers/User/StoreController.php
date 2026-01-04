<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;
use App\Services\StoreRecommendService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;



class StoreController extends Controller
{
    public function show($store, StoreRecommendService $service)
    {
        $storeModel = Store::query()
            ->where('id',(int)$store)
            ->select('stores.*')
            ->selectSub(function ($q) {
                $q->from('reviews')
                ->selectRaw('AVG(`reviews`.`rating`)')
                ->whereColumn('reviews.store_id', 'stores.id'); 
            }, 'reviews_avg_rating')
            ->where('stores.id', (int)$store)  
            ->firstOrFail();

        $reviews = Review::with(['user','store'])
            ->where('store_id', (int)$store)
            ->latest()
            ->take(10)
            ->get();

        return view('pages.user.stores.show', [
            'store' => $storeModel,
            'reviews' => $reviews,
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
            ->with('success', '予約完了！');
    }

}

