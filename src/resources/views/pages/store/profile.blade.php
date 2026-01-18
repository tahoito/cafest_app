@extends('layouts.app')
@section('title','店舗情報')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
  <div class="h-full overflow-y-auto">
        <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('store.top') }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>

                <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                    店舗情報
                </h1>
                </div>
            </div>
        </header>

        @php 
            $days = ['日','月','火','水','木','金','土'];
            $pm = $store->paymentMethods->pluck('name')->filter()->values();
            $sns = [
                'tiktok' => $store->tiktok_url, 
                'instagram' => $store->instagram_url,
                'x' => $store->x_url,
                'website' => $store->website_url,
            ];
            $hasSns = collect($sns)->filter()->isNotEmpty();
        @endphp

        <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
            <div class="w-full max-w-md mx-auto pt-5 space-y-6 pb-4">
                <section class="px-4">
                    <div class="flex items-center justify-between">
                        <div class="text-lg text-text_color">基本情報</div>
                        <a href="{{ route('store.profile.edit.basic') }}"
                            class="flex items-center gap-1 text-sm text-text_color hover:opacity-80">
                        <x-icons.edit class="w-[15px] h-[15px] text-text_color"/>編集</a>
                    </div>
                    <div class="mt-2 rounded-lg bg-form ring-1 ring-gray-200 shadow-[0_2px_10px_rgba(0,0,0,0.10)] overflow-hidden">
                        <div class="divide-y divide-line">
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-1.5 text-main2">
                                    <x-icons.store stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">店舗名</div>
                                </div>
                                <div class="text-base {{ $store->mood ? 'text-text_color' : 'text-placeholder' }}">{{ $store->name ?? '未設定です'}}</div>
                            </div>
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-1.5 text-main2">
                                    <x-icons.access stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">住所</div>
                                </div>
                                <div class="text-base {{ $store->mood ? 'text-text_color' : 'text-placeholder' }}">{{ $store->address ?? '未設定です'}}</div>
                            </div>
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-1.5 text-main2">
                                    <x-icons.pin stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">エリア</div>
                                </div>
                                <div class="text-base {{ $store->mood ? 'text-text_color' : 'text-placeholder' }}">{{ $store->area ?? '未設定です'}}</div>
                            </div>
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-1.5 text-main2">
                                    <x-icons.mycafe stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">カテゴリー</div>
                                </div>
                                <div class="text-base {{ $store->mood ? 'text-text_color' : 'text-placeholder' }}">{{ $store->mood ?? '未設定です'}}</div>
                            </div>
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-1.5 text-main2">
                                    <x-icons.time stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">営業時間</div>
                                </div>
                                <div class="text-base text-text_color">
                                    @if($store->hours->isNotEmpty())
                                        <div class="space-y-1">
                                            @foreach($store->hours as $h)
                                            <div>
                                                {{ $days[$h->day_of_week] }}：
                                                @if($h->is_closed)
                                                定休日
                                                @else
                                                {{ \Carbon\Carbon::parse($h->open_time)->format('H:i') }}
                                                -
                                                {{ \Carbon\Carbon::parse($h->close_time)->format('H:i') }}
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                    <span class="text-placeholder">未設定です</span>
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-1.5 text-main2">
                                    <x-icons.money stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">予算</div>
                                </div>
                                <div class="text-base text-text_color">
                                    @if($store->budget_min && $store->budget_max)
                                        {{ $store->budget_min }}円~{{ $store->budget_max }}円
                                    @else   
                                        <span class="text-placeholder">未設定です</span>
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-1.5 text-main2">
                                    <x-icons.wallet stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">支払い方法</div>
                                </div>
                                <div class="text-base">
                                    @forelse($store->paymentMethods as $pm)
                                        <div class="text-text_color">{{ $pm->name }}</div>
                                    @empty
                                        <span class="text-placeholder">未設定です</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-placeholder text-right">最終更新12/10</div>
                </section>

                <section class="px-4">
                    <div class="flex items-center justify-between">
                        <div class="text-lg text-text_color">店舗紹介</div>
                        <a href="{{ route('store.profile.edit.description') }}"
                            class="flex items-center gap-1 text-sm text-text_color hover:opacity-80">
                        <x-icons.edit class="w-[15px] h-[15px] text-text_color"/>編集</a>
                    </div>
                    <div class="mt-2 rounded-lg bg-form ring-1 ring-gray-200 px-4 py-4 shadow-[0_2px_10px_rgba(0,0,0,0.10)] overflow-hidden">
                        <div class="text-base {{ filled($store->description) ? 'text-text_color' : 'text-placeholder' }}">
                            {{ filled($store->description) ? $store->description : '未設定です' }}
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-placeholder text-right">最終更新12/10</div>
                </section>

                <section class="px-4">
                    <div class="flex items-center justify-between">
                        <div class="text-lg text-text_color">連絡情報</div>
                        <a href="{{ route('store.profile.edit.contact') }}"
                            class="flex items-center gap-1 text-sm text-text_color hover:opacity-80">
                        <x-icons.edit class="w-[15px] h-[15px] text-text_color"/>編集</a>
                    </div>
                    <div class="mt-2 rounded-lg bg-form ring-1 ring-gray-200 shadow-[0_2px_10px_rgba(0,0,0,0.10)] overflow-hidden">
                        <div class="divide-y divide-line">
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-3 text-main2">
                                    <x-icons.mail stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">メールアドレス</div>
                                </div>
                                <div class="text-base {{ $store->mood ? 'text-text_color' : 'text-placeholder' }}">{{ $store->email ?? '未設定です'}}</div>
                            </div>
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-3 text-main2">
                                    <x-icons.phone stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">電話番号</div>
                                </div>
                                <div class="text-base {{ $store->mood ? 'text-text_color' : 'text-placeholder' }}">{{ $store->phone ?? '未設定です'}}</div>
                            </div>
                            <div class="grid grid-cols-[170px_1fr] items-center px-4 py-4">
                                <div class="grid grid-cols-[20px_auto] items-center gap-3 text-main2">
                                    <x-icons.instagram stroke="1.5" class="h-5 w-5 shrink-0 text-main2" />
                                    <div class="text-sm font-medium">SNSリンク</div>
                                </div>
                                <div class="flex items-center gap-3">
                                @if($hasSns)
                                @foreach($sns as $type => $url)
                                    @if($url)
                                    <a href="{{ $url }}" target="_blank"
                                        class="text-text_color hover:text-main transition">
                                        <x-icons.{{ $type }} class="h-6 w-6" />
                                    </a>
                                    @endif
                                @endforeach
                                @else
                                <span class="text-placeholder text-base">未設定です</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-sm text-placeholder text-right">最終更新12/10</div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
