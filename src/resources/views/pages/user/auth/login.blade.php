@extends('layouts.app')
@section('title','ログイン')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-base">
    <div class="w-full max-w-md px-4">

        <div class="mb-12 flex flex-col items-center">
            <x-icons.user class="w-20 h-20 text-text mb-2"/>
            <h1 class="text-center text-text text-2xl">
                ログイン    
            </h1>
        </div>

        <form method="POST" action="{{ route('user.login') }}">
        @csrf

        <div class="space-y-8">
            {{-- email --}}
            <div class="space-y-1">
            <x-ui.label for="email">メールアドレス</x-ui.label>
            <x-ui.input
                id="email"
                type="email"
                name="email"
                placeholder="メールアドレスを入力"
                required
                autocomplete="off"
            />
            </div>

            {{-- password --}}
            <div class="space-y-1">
            <x-ui.label for="password">パスワード</x-ui.label>
            <x-ui.input
                id="password"
                type="password"
                name="password"
                placeholder="パスワードを入力"
                required
                autocomplete="new-password"
            />
            </div>

            {{-- button --}}
            <div class="flex justify-center pt-3">
            <x-ui.button type="submit" class="w-[90%]">
                ログイン
            </x-ui.button>
            </div>
        </div>
        </form>

        <div class="mt-4 text-center text-sm text-text">
            サインアップは
            <a href="#" class="text-main hover:underline">    
                こちら
            </a>
        </div>
    </div>
</div>