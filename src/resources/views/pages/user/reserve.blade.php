@extends('layouts.app')
@section('title','予約')

@section('content')
<div class="min-h-screen bg-base_color">
    <x-ui.reserve-card
    :reservation="[
        'shop_name' => 'Cafe Latte',
        'image_url' => 'https://picsum.photos/400/300',
        'reserved_date' => '2026-01-05',
        'reserved_time' => '18:30',
        'guest_count' => 2,
        'id' => 1,
    ]"
    />    
</div>
@endsection