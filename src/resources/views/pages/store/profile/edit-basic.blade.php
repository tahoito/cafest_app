@extends('layouts.app')
@section('title','基本情報')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
    <div class="h-full overflow-y-auto">
        <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
            <div class="pt-[env(safe-area-inset-top)]">
                <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
                <a class="p-2" href="{{ route('store.profile') }}">
                    <x-icons.back class="w-5 h-5 text-text_color" />
                </a>

                <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
                    基本情報
                </h1>
                </div>
            </div>
        </header>


        @php 
            $days = [ '日','月','火','水','木','金','土'];
            $leftDays = [ 0 => '日', 1 => '月', 2 => '火', 3 => '水'];
            $rightDays = [ 4 => '木', 5 => '金', 6=> '土'];
            $hoursByDay = $store->hours->keyBy('day_of_week');
            $openDaysDefault = $store->hours    
                ->filter(fn($h) => !$h->is_closed)
                ->pluck('day_of_week')
                ->map(fn($v) => (string)$v)
                ->toArray();
            $openDays = old('open_days', $openDaysDefault);
            $sameSource = $hoursByDay->get(1);
            if (!$sameSource) {
                $sameSource = $store->hours->first(fn($h) => !$h->is_closed && $h->open_time && $h->close_time);
            }

            $sameOpenDefault  = optional($sameSource)->open_time ? substr($sameSource->open_time, 0, 5) : '';
            $sameCloseDefault = optional($sameSource)->close_time ? substr($sameSource->close_time, 0, 5) : '';
            $sameOpenValue  = old('same_open', $sameOpenDefault);
            $sameCloseValue = old('same_close', $sameCloseDefault);

            $times = [];
            for ($h=0; $h<24; $h++) {
                foreach ([0,30] as $m) {
                    $times[] = sprintf('%02d:%02d', $h, $m);
                }
            }

            $ranges = [
                '0-1000' => '〜1,000円',
                '1000-2000' => '1,000~2,000円',
                '2000-3000' => '2,000~3,000円',
                '3000-5000' => '3,000~5,000円',
                '5000-' => '5,000円〜',
            ];

            $defaultRange = '';
            if ($store->budget_min !== null || $store->budget_max !== null) {
                $min = $store->budget_min;
                $max = $store->budget_max;

                $minStr = ($min === null) ? '' : (string)$min;   // 0でも"0"にする
                $maxStr = ($max === null) ? '' : (string)$max;

                $defaultRange = "{$minStr}-{$maxStr}";
            }


            $payments = [
                'cash' => '現金',
                'card' => 'クレジットカード',
                'ic' => '交通系IC',
                'paypay' => 'PayPay',
            ];

            $selectedDefault = $store->paymentMethods->pluck('slug')->toArray();
            $selectedPayments = old('payments', $selectedDefault);
        @endphp 

        <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
            <div class="w-full max-w-md mx-auto pt-5 space-y-6 pb-4">
                <section class="px-4">
                    <form method="POST" action="{{ route('store.profile.update.basic') }}" class="space-y-6"
                        x-data="{
                            areas: ['栄','名駅','大須','伏見','上前津','金山','矢場町','鶴舞','星ヶ丘','八事','桜山','今池','本山','覚王山','新瑞橋','久屋大通'],
                            moods: ['珈琲専門','紅茶','スイーツ','夜カフェ','静かめ','勉強・作業','長居OK','レトロ・喫茶','女子会向け','デート向け','韓国風','ペットOK'],

                            selectedArea: @js(old('area',$store->area)),
                            selectedMood: @js(old('mood',$store->mood)),

                            selectArea(v){ this.selectedArea = (this.selectedArea === v) ? null : v},
                            selectMood(v){ this.selectedMood = (this.selectedMood === v) ? null : v},
                        }"
                    >
                        @csrf 
                        @method('PATCH')

                        <input type="hidden" name="area" :value="selectedArea ?? ''">
                        <input type="hidden" name="mood" :value="selectedMood ?? ''">
                       
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <x-ui.label for="name">店舗名</x-ui.label>
                                <x-ui.input
                                    id="name"
                                    type="name"
                                    name="name"
                                    placeholder="店舗名を入力"
                                    value="{{ old('name', $store->name) }}"
                                    required
                                />
                            </div>
                    
                            <div class="space-y-1">
                                <x-ui.label for="store">住所</x-ui.label>
                                <x-ui.input
                                    id="address"
                                    type="address"
                                    name="address"
                                    placeholder="住所を入力"
                                    value="{{ old('address', $store->address) }}"
                                    required
                                />
                            </div>
                        </div>

                       
                        <div class="space-y-1">
                            <div class="text-lg text-text_color font-medium">エリア</div>
                            <div class="grid grid-cols-4 gap-2">
                                <template x-for="(a, index) in areas" :key="index">
                                    <x-ui.chip
                                        type="button"
                                        x-bind:data-value="a"
                                        @click="selectArea($el.dataset.value)"
                                        x-bind:class="selectedArea === a ? '!bg-main !text-form' : '!bg-accent text-text_color'"
                                        >
                                        <span x-text="a"></span>
                                    </x-ui.chip>
                                </template>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <div class="text-lg text-text_color font-medium">カテゴリー</div>
                            <div class="grid grid-cols-3 gap-3 mt-3">
                                <template x-for="(m, index) in moods" :key="index">
                                    <x-ui.chip
                                        type="button"
                                        variant="mood"
                                        x-bind:data-value="m"
                                        @click="selectMood($el.dataset.value)"
                                        x-bind:class="selectedMood === m ? '!bg-main !text-form' : '!bg-accent text-text_color'"
                                        >
                                        <span x-text="m"></span>
                                    </x-ui.chip>
                                </template>
                            </div>
                        </div>
                    
                        <div class="space-y-1">
                            <div class="text-lg text-text_color font-medium">営業曜日</div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-3">
                                    @foreach($leftDays as $i => $label)
                                    <label class="flex items-center gap-3 rounded-lg bg-form px-4 py-3 ring-1 ring-gray-200">
                                        <input type="checkbox" name="open_days[]" value="{{ $i }}"
                                            @checked(in_array((string)$i, array_map('strval', $openDays), true))
                                            class="peer h-5 w-5 accent-main2"
                                        >
                                        <span class="text-text_color">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                <div class="space-y-3">
                                    @foreach($rightDays as $i => $label)
                                    <label class="flex items-center gap-3 rounded-lg bg-form px-4 py-3 ring-1 ring-gray-200">
                                        <input type="checkbox" name="open_days[]" value="{{ $i }}"
                                            @checked(in_array((string)$i, array_map('strval', $openDays), true))
                                            class="peer h-5 w-5 accent-main2"
                                        >
                                        <span class="text-text_color">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3" x-data="{ hoursMode: @js(old('hours_mode', 'same')), is24h: @js((int) old('is_24h',0)) === 1, }">
                            <input type="hidden" name="hours_mode" :value="hoursMode">
                            <input type="hidden" name="is_24h" :value="is24h ? 1 : 0">
                        
                            <div class="text-lg text-text_color font-medium">営業時間</div>

                            <div class="space-y-2">
                                <label class="flex items-center gap-3 rounded-lg bg-form px-4 py-3 ring-1 ring-gray-200">
                                <input type="checkbox" x-model="is24h" class="h-5 w-5 accent-main2">
                                <span class="text-text_color">24時間営業</span>
                                </label>

                                <div x-show="!is24h" x-cloak class="space-y-2">
                                <label class="flex items-center gap-3 rounded-lg bg-form px-4 py-3 ring-1 ring-gray-200">
                                    <input type="radio" value="same" x-model="hoursMode" class="h-5 w-5 accent-main2">
                                    <span class="text-text_color">全て同じ時間</span>
                                </label>

                                <label class="flex items-center gap-3 rounded-lg bg-form px-4 py-3 ring-1 ring-gray-200">
                                    <input type="radio" value="byDay" x-model="hoursMode" class="h-5 w-5 accent-main2">
                                    <span class="text-text_color">曜日ごとに設定する</span>
                                </label>
                                </div>
                            </div>

                            <div x-show="is24h" x-cloak
                                class="rounded-lg bg-main2/10 px-4 py-4 ring-1 ring-main2 text-main2 text-sm">
                                24時間営業です
                            </div>

                            <div x-show="!is24h && hoursMode === 'same'" x-cloak class="py-4"
                                x-data="{ open: @js($sameOpenValue), close: @js($sameCloseValue) }">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <select name="same_open" x-model="open"
                                            :class="open ? 'text-text_color' : 'text-placeholder'"
                                            class="w-full rounded-lg px-4 py-3 ring-1 ring-gray-200">
                                            <option value="" disabled>開店時間</option>
                                            @foreach($times as $t)
                                                <option value="{{ $t }}" @selected(old('same_open', $sameOpenDefault) === $t)>{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <select name="same_close" x-model="close"
                                            :class="close ? 'text-text_color' : 'text-placeholder'"
                                            class="w-full rounded-lg px-4 py-3 ring-1 ring-gray-200">
                                            <option value="" disabled>閉店時間</option>
                                            @foreach($times as $t)
                                                <option value="{{ $t }}" @selected(old('same_close', $sameCloseDefault) === $t)>{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div x-show="!is24h && hoursMode === 'byDay'" x-cloak class="space-y-2">
                                @foreach($days as $i => $label)
                                    @php $dayHour = $hoursByDay->get($i); @endphp
                                <div class="flex items-center justify-between rounded-lg bg-form px-4 py-3 ring-1 ring-gray-200">
                                    <span class="text-text_color font-medium">{{ $label }}</span>
                                    <div class="flex items-center gap-2">
                                        <select  name="hours[{{ $i }}][open]" 
                                            class="w-[120px] rounded-lg bg-white px-2 py-2 ring-1 ring-gray-200 text-text_color">
                                            <option value="">--:--</option>
                                            @foreach($times as $t)
                                                <option value="{{ $t }}" @selected(old("hours.$i.open", optional($dayHour)->open_time) === $t)>{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    <span class="text-placeholder">-</span>
                                        <select name="hours[{{ $i }}][close]" 
                                            class="w-[120px] rounded-lg bg-white px-2 py-2 ring-1 ring-gray-200 text-text_color">
                                            <option value="">--:--</option>
                                            @foreach($times as $t)
                                                <option value="{{ $t }}" @selected(old("hours.$i.close", optional($dayHour)->close_time) === $t)>{{ $t }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-1">
                            <div class="text-lg text-text_color font-medium">予算</div>
                            <select name="budget_range"
                                :class="'text-text_color'"
                                class="w-full rounded-lg bg-form px-4 py-3 ring-1 ring-gray-200">
                                @foreach($ranges as $val => $label)
                                    <option value="{{ $val }}" @selected(old('budget_range', $defaultRange) === $val)>
                                    {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <div class="text-lg text-text_color font-medium">支払い方法</div>
                            <div class="py-3 space-y-3">
                                @foreach($payments as $key => $label)
                                <label class="flex items-center gap-3 rounded-lg bg-form px-4 py-3 ring-1 ring-gray-200">
                                    <input type="checkbox" name="payments[]" value="{{ $key }}"
                                    @checked(in_array($key, (array)$selectedPayments, true))
                                    class="peer h-5 w-5 accent-main2"
                                >
                                    <span class="text-text_color">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="pt-4">
                            <x-ui.button type="submit" theme="store" class="w-full text-form">
                                保存
                            </x-ui.button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
