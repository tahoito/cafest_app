<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class StoreReserveController extends Controller
{
    public function index() {
        $reservations = Reservation::where('store_id',auth('store')->id())
            ->whereNull('visited_at')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('pages.store.reserve', compact('reservations'));
    }

    public function visit(Reservation $reservation, Request $request) {

        abort_unless($reservation->store_id === auth('store')->id(), 403);
        $reservation->update(['visited_at' => now()]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success','来店済みにしました');
    }
}
