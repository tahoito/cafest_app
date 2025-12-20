@extends('layouts.app')
@section('title','アカウント情報設定')

@section('content')
<div class="min-h-screen bg-base relative overflow-hidden">
    <div class="absolute top-8 inset-x-0 grid grid-cols-[48px_1fr_48px] items-center px-4 z-50">
        <a href="{{ route('user.signup') }}" class="p-2 justify-self-start">
            <x-icons.back class="w-5 h-5 text-text" />
        </a>

        <h1 class="text-center text-text text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
            アカウント情報設定
        </h1>

        <div></div>
    </div>


    {{-- main content --}}
    <div class="flex items-center justify-center min-h-screen">
       
    </div>

</div>
@endsection
