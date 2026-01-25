@extends('layouts.app')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
    <div class="h-full overflow-y-auto">
            <header
                x-data="favoriteFolderHeader(
                    @json($folderName ?? $title ?? ''),
                    @json(!empty($folderId)
                    ? route('user.mycafe.favorites.update', ['folder'=>$folderId])
                    : null
                    )
                )"
            >
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('user.mycafe') }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>

                <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis" x-text="name">
                </h1>

                {{-- ︙メニュー（フォルダ詳細の時だけ） --}}
                @if(!empty($folderId))
                    <div class="flex justify-end">

                    {{-- ︙ボタン --}}
                    <button type="button" class="p-2 rounded-full" @click="open=true" aria-label="More">
                        <x-icons.more-menu class="text-center" />
                    </button>

                    {{-- 下シート --}}
                    <div x-show="open" x-cloak class="fixed inset-0 z-[60]">
                        <button type="button"
                                class="absolute inset-0 bg-black/40"
                                @click="open=false"
                                aria-label="Close"></button>

                        <div class="absolute inset-x-0 bottom-0">
                        <div class="bg-form px-5 pt-3 pb-4 rounded-t-3xl">
                            <div class="mx-auto mb-2 h-1.5 w-12 rounded-full bg-line"></div>

                            <div class="relative flex items-center justify-center">
                            <button
                                type="button"
                                class="absolute left-0 grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
                                aria-label="閉じる"
                                @click="open=false"
                            >
                                <x-icons.close class="w-[24px] h-[24px] text-text_color" />
                            </button>
                            </div>
                        </div>

                        <div class="mx-auto w-full max-w-md bg-base_color" @click.stop>
                            <div class="px-4 py-4 space-y-3">

                            
                            <button
                                type="button"
                                class="mx-auto block h-12 w-[260px]
                                    rounded-full border-2 border-main bg-main
                                    text-sm text-form
                                    shadow-[0_4px_10px_rgba(0,0,0,0.18)]"
                                @click="open=false; $nextTick(() => showEdit=true)"
                            >
                                編集する
                            </button>

                            
                            <button
                                type="button"
                                class="mx-auto block h-12 w-[260px]
                                    rounded-full border-2 border-main bg-form
                                    text-sm text-text_color
                                    shadow-[0_4px_10px_rgba(0,0,0,0.18)]"
                                @click="open=false; $nextTick(() => showDelete=true)"
                            >
                                削除する
                            </button>

                            </div>
                        </div>

                        <div class="h-[env(safe-area-inset-bottom)] bg-form"></div>
                        </div>
                    </div>

                    <div x-show="showEdit" x-cloak class="fixed inset-0 z-[70]">
                        <button class="absolute inset-0 bg-black/40"
                            @click="showEdit=false; error=''"></button>

                        <div class="relative h-full w-full grid place-items-center px-6">
                            <div class="relative w-full max-w-sm rounded-2xl bg-base_color p-6" @click.stop>

                            <div class="flex items-center justify-between">
                                <button class="grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
                                @click="showEdit=false; error=''">
                                <x-icons.close class="w-6 h-6 text-text_color" />
                                </button>
                                <div class="text-lg text-text_color">コレクション名を編集</div>
                                <div class="w-9"></div>
                            </div>

                            <form
                                @submit.prevent="saveName" class="mt-6 space-y-6"
                            >
                                @csrf
                                @method('PATCH')

                                <x-ui.input
                                    type="text"
                                    name="name"
                                    x-model="name"
                                    placeholder="放課後行きたいカフェ"
                                />

                                <x-ui.button type="submit" class="w-full">
                                保存する
                                </x-ui.button>
                            </form>

                            <div x-show="error" class="mt-3 text-sm text-notification" x-text="error"></div>
                            </div>
                        </div>
                    </div>


                    {{-- 中央モーダル（削除確認） --}}
                    <div x-show="showDelete" x-cloak class="fixed inset-0 z-[70]">
                        <button
                        type="button"
                        class="absolute inset-0 bg-black/40"
                        @click="showDelete=false"
                        aria-label="Close"
                        ></button>

                        <div class="relative h-full w-full grid place-items-center px-6">
                        <div
                            class="relative w-full max-w-sm rounded-2xl bg-form shadow-[0_10px_30px_rgba(0,0,0,0.35)] p-6"
                            @click.stop
                        >
                            <button
                            type="button"
                            class="absolute left-4 top-4 grid h-9 w-9 place-items-center rounded-full hover:bg-black/5"
                            @click="showDelete=false"
                            aria-label="閉じる"
                            >
                            <x-icons.close class="w-5 h-5 text-text_color" />
                            </button>

                            <div class="pt-8 text-center">
                            <div class="text-text_color text-lg font-medium">
                                コレクションを削除しますか？
                            </div>

                            <form
                                method="POST"
                                action="{{ route('user.mycafe.favorites.destroy', ['folder' => $folderId]) }}"
                                class="mt-6 space-y-4"
                            >
                                @csrf
                                @method('DELETE')

                                <x-ui.button type="submit" class="w-full">
                                    削除する
                                </x-ui.button>

                                <button
                                type="button"
                                class="w-full text-text_color text-sm"
                                @click="showDelete=false"
                                >
                                    キャンセル
                                </button>
                            </form>
                            </div>

                        </div>
                        </div>
                    </div>

                    </div>
                @endif

                </div>
            </div>
            </header>

            {{-- 本文 --}}
            <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
            <div class="w-full mx-auto px-4 pt-4 py-4 space-y-5">
                @if (($stores ?? collect())->isEmpty())
                <div class="text-placeholder text-center text-sm">
                    お気に入りの店舗がまだありません
                </div>
                @else
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($stores as $store)
                    <x-ui.card.store
                        :store="$store"
                        :faved="in_array(data_get($store,'id'), $favIds)"
                        :href="route('user.stores.show', ['store' => data_get($store,'id')])"
                        variant="grid"
                    />
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
    @endsection
