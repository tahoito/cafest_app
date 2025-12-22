@extends('layouts.app')
@section('title','アカウント情報設定')

@section('content')
<div class="min-h-screen bg-base relative overflow-hidden">

  {{-- header --}}
    <header class="sticky top-0 z-50 bg-base">
        <div class="pt-[env(safe-area-inset-top)]">
            <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
            <a class="p-2" href="{{ route('user.signup') }}">
                <x-icons.back class="w-5 h-5 text-text" />
            </a>

            <h1 class="text-center text-text text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                アカウント情報設定
            </h1>
            <div></div>
            </div>
        </div>
    </header>


  {{-- content --}}
    <main class="w-full max-w-md mx-auto px-4 oy-6 pb-24">
        <form method="POST" action="{{ route('user.settings') }}">
        @csrf

        <section class="flex justify-center pt-8">
            <label class="cursor-pointer">
                <div class="w-56 h-56 rounded-xl bg-base border border-accent shadow-sm flex flex-col items-center justify-center gap-3">
                    <div class="w-16 h-16 text-placeholder">
                        <img
                            id="preview"
                            class="w-full h-full object-cover rounded-lg hidden"
                        />
                        <x-icons.image />
                    </div>
                    <span class="text-xs text-text">アイコン</span>
                </div>
                <input 
                    type="file"
                    name="icon" 
                    accept="image/*" 
                    class="hidden"
                    onchange="
                    const img = document.getElementById('preview');
                    img.src = window.URL.createObjectURL(this.files[0]);
                    img.classList.remove('hidden');
                " />
            </label>
        </section>

        <section class="space-y-2 pt-8">
            <x-ui.label for="username">ユーザー名</x-ui.label>
            <x-ui.input 
                id="username" 
                type="text" 
                name="username" 
                placeholder="ユーザー名を入力"/>
        </section>
       
        <section 
            class="space-y-3 pt-8"
                x-data="{
                    open: false,
                    areas: ['栄','名駅','大須','上前津','金山','矢場町','鶴舞','星ヶ丘','八事','桜山','今池','覚王山','新瑞橋','久屋大通'],
                    selected: []
                }"
        >
            <div>
                <div class="text-lg text-text font-medium">おすすめで出して欲しいエリア</div>
                <div class="text-xs text-text">※複数選択可</div>
            </div>

            {{-- チップ選択肢 --}}
            <div class="grid grid-cols-4 gap-2 mt-3">
            </div>
        </section>

        <section class="space-y-3 pt-8">
            <div>
                <div class="text-lg text-text font-medium">好みのカフェの雰囲気</div>
                <div class="text-xs text-text">※複数選択可</div>
            </div>
        </section>

        <div class="flex justify-center pt-8">
            <x-ui.button type="submit" variant="secondary" class="w-full text-form">
                確認
            </x-ui.button>
        </div>
        </form>
    </main>
</div>

@endsection
