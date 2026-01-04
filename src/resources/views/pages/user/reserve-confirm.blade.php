@extends('layouts.app')

@section('hideNavbar')
@endsection

@section('content')
  <div class="min-h-dvh bg-base_color flex flex-col overflow-hidden">
    <header class="sticky top-0 z-50 bg-base_color">
      <div class="pt-[env(safe-area-inset-top)]">
        <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
          <a class="p-2" href="{{ route('user.stores.show', $store) }}">
            <x-icons.back class="w-5 h-5 text-text_color" />
          </a>

          <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
            予約情報確認
          </h1> 
          <div></div>
        </div>
      </div>
    </header>

    <main class="flex-1 overflow-y-auto">
      <div class="w-full max-w-md mx-auto px-3 pt-6 space-y-8">

        <section class="rounded-2xl border border-main bg-base px-5 py-4 shadow-[0_2px_10px_rgba(0,0,0,0.10)]">
          <div class="grid grid-cols-[120px_1fr] gap-y-3 items-center">

            <div class="grid grid-cols-[24px_auto] items-center gap-2 text-text_color">
              <x-icons.store stroke="2" class="h-6 w-6 shrink-0 text-text_color" />
              <div class="text-base font-medium">店舗名</div>
            </div>
            <div class="text-base text-text_color">
              {{ $store->name }}
            </div>

            <div class="grid grid-cols-[24px_auto] items-center gap-2 text-text_color">
              <x-icons.date class="h-7 w-7 shrink-0 text-text_color" />
              <div class="text-base font-medium">日付</div>
            </div>
            <div class="text-base text-text_color">
              {{ data_get($data, 'date') }}
            </div>

            <div class="grid grid-cols-[24px_auto] items-center gap-2 text-text_color">
              <x-icons.time class="h-6 w-6 shrink-0 text-text_color" />
              <div class="text-base font-medium">時間</div>
            </div>
            <div class="text-base text-text_color">
              {{ data_get($data, 'start_time') }}-{{ data_get($data, 'end_time') }}
            </div>

            <div class="grid grid-cols-[24px_auto] items-center gap-2 text-text_color">
              <x-icons.number class="h-7 w-7 shrink-0 text-text_color" />
              <div class="text-base font-medium">人数</div>
            </div>
            <div class="text-base text-text_color">
              {{ data_get($data, 'people') }}名
            </div>

          </div>
        </section>

        @if($errors->has('time'))
          <p class="text-center text-notification text-sm">{{ $errors->first('time') }}</P>
        @endif

        <form method="POST" action="{{ route('user.stores.reserve.store', $store) }}" class="space-y-8">
          @csrf
          @foreach($data as $k => $v)
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
          @endforeach

          {{-- inputs --}}
          <section class="space-y-8">
            <div class="space-y-2">
              <div class="text-text_color text-lg font-medium">名前</div>
              <x-ui.input
                id="name"
                type="text"
                name="name"
                placeholder="名前を入力"
                required
                autocomplete="off"
                value="{{ old('name') }}"
              />
            </div>

            <div class="space-y-2">
              <div class="text-text_color text-lg font-medium">電話番号</div>
              <x-ui.input
                id="phone"
                type="text"
                name="phone"
                placeholder="電話番号を入力"
                required
                autocomplete="off"
                value="{{ old('phone') }}"
              />
            </div>
          </section>

          <div class="h-32"></div>
          <div class="sticky bottom-0 left-0 w-full bg-base_color pt-4">
            <p class="text-center text-main text-sm pb-3">
              10分以上ご来店がない場合、キャンセルとなります。
            </p>
            <x-ui.button :type="'submit'" class="w-full text-form">
              予約確定
            </x-ui.button>
          </div>
        </form>
      </div>
    </main>
  </div>
@endsection
