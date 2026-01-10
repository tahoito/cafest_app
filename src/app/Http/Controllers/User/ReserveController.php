<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class ReserveController extends Controller
{
    public function index()
    {
        $reservations = Reservation::query()
            ->with(['store.slideImages'])
            ->where('user_id', auth('user')->id())
            ->where('status', '!=', 'canceled') 
            ->orderByDesc('start_at')
            ->get();

        return view('pages.user.reserve', compact('reservations'));
    }

    public function destroy(Reservation $reservation)
    {
        abort_unless($reservation->user_id === auth('user')->id(), 403);
        $reservation->update(['status' => 'canceled']);

        return redirect()->route('user.reserve')->with('success', '予約をキャンセルしました');
    }
}
