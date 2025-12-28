@extends('layouts.app')

@section('content')

    @php
    $dummy = (object)[
    'shop_name' => 'cafest',
    'rating' => 4.5,
    'body' => '雰囲気がとても良くて、スイーツも最高でした。',
    'created_at' => now(),
    ];
    @endphp
    <x-ui.review-card :review="$dummy" />
    
@endsection

