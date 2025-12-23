@extends('layouts.app')
@section('title','店舗情報設定')

@section('content')
<div class="min-h-screen bg-base relative overflow-hidden">

  {{-- header --}}
    <header class="sticky top-0 z-50 bg-base_color">
        <div class="pt-[env(safe-area-inset-top)]">
            <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
            <a class="p-2" href="{{ route('store.signup') }}">
                <x-icons.back class="w-5 h-5 text-text" />
            </a>

            <h1 class="text-center text-text text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                店舗情報設定
            </h1>
            <div></div>
            </div>
        </div>
    </header>


  {{-- content --}}
    <main class="w-full max-w-md mx-auto px-4 oy-6 pb-24">
        <form method="POST" action="{{ route('store.settings.store') }}"
        x-data="{
            areas: ['栄','名駅','大須','上前津','金山','矢場町','鶴舞','星ヶ丘','八事','桜山','今池','覚王山','新瑞橋','久屋大通'],
            moods: ['珈琲専門','紅茶','スイーツ','夜カフェ','静かめ','勉強・作業','長居OK','レトロ・喫茶','女子会向け','デート向け','韓国風','ペットOK'],
            selectedAreas: [],
            selectedMoods: [],
            toggle(list, value){
            if(list.includes(value)) return list.splice(list.indexOf(value), 1)
            list.push(value)
            }
        }"
        >
        @csrf
        <input type="hidden" name="selected_areas" :value="JSON.stringify(selectedAreas)" />
        <input type="hidden" name="selected_moods" :value="JSON.stringify(selectedMoods)" />
        <section class="space-y-2 pt-8">
            <x-ui.label for="name">店舗名（正式名称）</x-ui.label>
            <x-ui.input 
                id="name" 
                type="text" 
                name="name" 
                placeholder="店舗正式名称を入力"/>
        </section>

        <section class="space-y-2 pt-8">
            <x-ui.label for="address">店舗住所</x-ui.label>
            <x-ui.input 
                id="storename" 
                type="text" 
                name="address" 
                placeholder="名古屋市から入力"/>
        </section>  

        <section class="space-y-3 pt-8">
            <div>
                <div class="text-lg text-text font-medium">店舗のエリア選択</div>
            </div>

            {{-- チップ選択肢 --}}
            <div class="grid grid-cols-4 gap-2 mt-3">
                <template x-for="(area, index) in areas" :key="index">
                    <x-ui.chip
                        variant="area"
                        @click="toggle(selectedAreas,area)"
                        x-bind:class="selectedAreas.includes(area) ? 'bg-main text-form' : 'bg-accent text-text'"
                    >
                        <span x-text="area"></span>
                    </x-ui.chip>
                </template>
            </div>
        </section>

        <section class="space-y-3 pt-8">
            <div>
                <div class="text-lg text-text font-medium">カテゴリー選択</div>
            </div>

            {{-- チップ選択肢 --}}
            <div class="grid grid-cols-3 gap-3 mt-3">
                <template x-for="(mood, index) in moods" :key="index">
                    <x-ui.chip
                        variant="mood"
                        @click="toggle(selectedMoods, mood)"
                        x-bind:class="selectedMoods.includes(mood) ? 'bg-main text-form' : 'bg-accent text-text'"
                    >
                        <span x-text="mood"></span>
                    </x-ui.chip>
                </template>
            </div>
        </section>

        <div class="flex justify-center pt-8">
            <x-ui.button type="submit" class="w-full text-form" theme="store">
                次へ
            </x-ui.button>
        </div>
        </form>
    </main>
</div>

@endsection
