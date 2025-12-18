@extends('layouts.app')
@section('title','ログイン')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-base relative overflow-hidden">
    <a href="{{ route('welcome') }}" class="fixed top-4 left-4 z-50 p-2">
        <x-icons.back class="w-5 h-5 text-text" />
    </a>

    <div class="w-full max-w-md px-4">
        <div class="mb-12 flex flex-col items-center">
            <x-icons.user size="80" stroke="1" class="text-text mb-2" />
            <h1 class="text-center text-text text-2xl">
                ログイン    
            </h1>
        </div>

        <form method="POST" action="{{ route('user.login') }}">
        @csrf

        <div class="space-y-4">
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
            <div class="relative">
                <x-ui.input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="パスワードを入力"
                    class="pr-10"
                    autocomplete="new-password"
                />
                <x-icons.eye target="#password" class="absolute right-3 top-1/2 -translate-y-1/2 text-placeholder w-5 h-5" />
            </div>
        
            {{-- button --}}
            <div class="flex justify-center pt-8">
            <x-ui.button type="submit" class="w-full">
                ログイン
            </x-ui.button>
            </div>
        </div>
        </form>

        <div class="mt-4 text-center text-sm text-text">
            サインアップの方は
            <a href="{{ route('user.signup') }}" class="text-main hover:underline">    
                こちら
            </a>
        </div>
    </div>
</div>