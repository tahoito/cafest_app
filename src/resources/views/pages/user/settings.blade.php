@extends('layouts.app')
@section('title','アカウント情報設定')

@section('content')
<div class="min-h-screen bg-base relative overflow-hidden">

  {{-- header --}}
    <header class="sticky top-0 z-50 bg-base_color">
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
    <main class="w-full max-w-md mx-auto px-4 py-6 pb-6">
        <form method="POST" action="{{ route('user.settings.store') }}" enctype="multipart/form-data"
            x-data="{
                showAllAreas: false,
                showAllMoods: false,
                areas: ['栄','名駅','大須','上前津','金山','矢場町','鶴舞','星ヶ丘','八事','桜山','今池','覚王山','新瑞橋','久屋大通'],
                moods: ['韓国風','デート向け','勉強・作業','夜カフェ','静かめ','レトロ・喫茶','ペットOK','女子向け','長居OK'],
                selectedAreas:[],
                selectedMoods: [],
                toggle(list, value){
                if(list.includes(value)) return list.splice(list.indexOf(value), 1)
                list.push(value)
                }
            }"
        >
        @csrf

        {{-- 送信用の隠しフィールド（Alpine で選択した配列を JSON にして送る） --}}
        <input type="hidden" name="area" :value="JSON.stringify(selectedAreas)" />
        <input type="hidden" name="mood" :value="JSON.stringify(selectedMoods)" />
        <section class="flex justify-center pt-8">
            <label class="cursor-pointer">
                <div
                class="w-56 h-56 rounded-xl bg-base border border-accent shadow-sm
                        flex items-center justify-center"
                >
                {{-- 内側ラッパー（余白用） --}}
                <div class="relative w-[88%] h-[88%] rounded-lg overflow-hidden
                            bg-base">

                    {{-- プレビュー画像 --}}
                    <img
                    id="preview"
                    class="absolute inset-0 w-full h-full object-cover hidden"
                    />

                    {{-- プレースホルダー --}}
                    <div
                    id="placeholder"
                    class="absolute inset-0 flex flex-col items-center justify-center
                            gap-3 text-placeholder"
                    >
                    <div class="w-14 h-14">
                        <x-icons.image />
                    </div>
                    <span class="text-xs text-text">アイコン</span>
                    </div>
                </div>
            </div>
            <input
                type="file"
                name="icon"
                accept="image/*"
                class="hidden"
                onchange="(function(){
                    const img = document.getElementById('preview');
                    const placeholder = document.getElementById('placeholder');
                    const file = this.files && this.files[0];
                    if(!file) return;

                    if(window.URL && typeof window.URL.createObjectURL === 'function'){
                    img.src = window.URL.createObjectURL(file);
                    img.onload = () => {
                        try{ window.URL.revokeObjectURL(img.src); }catch(e){}
                    };
                    } else {
                    const reader = new FileReader();
                    reader.onload = e => img.src = e.target.result;
                    reader.readAsDataURL(file);
                    }

                    img.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }).call(this)"
                />
            </label>
        </section>



        <section class="space-y-2 pt-8">
            <x-ui.label for="username">ユーザー名</x-ui.label>
            <x-ui.input 
                id="username" 
                type="text" 
                name="name" 
                placeholder="ユーザー名を入力"/>
        </section>
       
        <section class="space-y-3 pt-8">
            <div>
                <div class="text-lg text-text font-medium">おすすめで出して欲しいエリア</div>
                <div class="text-xs text-text">※複数選択可</div>
            </div>

            {{-- チップ選択肢 --}}
            <div class="grid grid-cols-4 gap-2 mt-3 overflow-hidden transition-all"
             :class="showAllAreas ? 'max-h-[999px]' : 'max-h-[72px]'">
                <template x-for="(area, index) in areas" :key="index">
                    <x-ui.chip
                        variant="area"
                        @click="toggle(selectedAreas, area)"
                        x-bind:class="selectedAreas.includes(area) 
                        ? 'bg-main text-form' : 'bg-accent text-text'"
                    >
                        <span x-text="area"></span>
                    </x-ui.chip>
                </template>
            </div>
            <button
                type="button"
                class="text-xs text-text ml-auto block"
                @click="showAllAreas = !showAllAreas"
            >
                <span x-text="showAllAreas ? '閉じる' : 'もっと見る'"></span>
            </button>
        </section>

        <section class="space-y-3 pt-8">
            <div>
                <div class="text-lg text-text font-medium">好みの雰囲気のカフェ</div>
                <div class="text-xs text-text">※複数選択可</div>
            </div>

            {{-- チップ選択肢 --}}
            <div class="grid grid-cols-3 gap-3 mt-3 overflow-hidden transition-all"
             :class="showAllMoods ? 'max-h-[999px]' : 'max-h-[104px]'">
                <template x-for="(mood, index) in moods" :key="index">
                    <x-ui.chip
                        variant="mood"
                        @click="toggle(selectedMoods, mood)"
                        x-bind:class="selectedMoods.includes(mood) 
                        ? 'bg-main text-form' : 'bg-accent text-text'"
                    >
                        <span x-text="mood"></span>
                    </x-ui.chip>
                </template>
            </div>
            <button
                type="button"
                class="text-xs text-text ml-auto block"
                @click="showAllMoods = !showAllMoods"
            >
                <span x-text="showAllMoods ? '閉じる' : 'もっと見る'"></span>
            </button>
        </section>

        <div class="flex justify-center pt-8">
            <x-ui.button type="submit" class="w-full text-form">
                次へ
            </x-ui.button>
        </div>
        </form>
    </main>
</div>

@endsection
