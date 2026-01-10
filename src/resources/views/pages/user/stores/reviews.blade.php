@extends('layouts.app')
@section('title','レビュー一覧')

@section('content')
@section('hideNavbar')
@endsection

@foreach ($reviews as $review)
  <x-ui.card.user.store-reviews :review="$review" />
@endforeach

@endsection