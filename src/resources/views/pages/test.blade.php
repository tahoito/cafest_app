@extends('layouts.app')

@section('content')


@php
$store = [
'id' => 1,
'name' => 'wiik coffee',
'area' => '栄',
'rating' => 4.2,
'mood' => '韓国風',
'image_url' => null,
];
@endphp

<x-ui.store-card :store="$store" variant="list" />
@endsection
