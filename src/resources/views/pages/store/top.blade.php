@extends('layouts.app')
@section('title','トップ')

@section('hideNavbar')
@endsection

@section('content')
<div class="min-h-screen bg-base_color">
  <div class="mx-auto w-full max-w-[393px] pt-10 pb-10">

    <div class="mx-auto max-w-[328px]">
      <div class="flex items-start justify-between text-text_color">
        <div>
          <h1 class="pt-4 text-2xl font-medium leading-tight">
            {{ $store->name }}
          </h1>

          <div class="mt-4 flex items-center gap-3">
            <div class="text-base">店舗ページ公開</div>

            <label class="inline-flex items-center cursor-pointer">
              <input
                id="togglePublic"
                type="checkbox"
                class="sr-only peer"
                @checked($store->is_public)
              />
              <div class="w-11 h-6 bg-gray-300 rounded-full relative transition
                          after:content-[''] after:absolute after:top-0.5 after:left-0.5
                          after:w-5 after:h-5 after:bg-white after:rounded-full after:transition
                          peer-checked:bg-main2 peer-checked:after:translate-x-5">
              </div>
            </label>
          </div>

          <p id="publicText" class="text-sm text-text_color mt-1">
            {{ $store->is_public ? '（公開中）' : '（非公開）' }}
          </p>
        </div>

        <button type="button" class="relative p-2">
          <x-icons.bell class="text-text_color" />
        </button>
      </div>
    </div>

    <div class="mt-8 grid grid-cols-[156px_156px] gap-4 w-fit mx-auto">
      <x-ui.top-item href="{{ route('store.profile') }}" label="店舗情報">
        <x-slot name="icon"><x-icons.info size="60" /></x-slot>
      </x-ui.top-item>

      <x-ui.top-item href="{{ route('store.image') }}" label="公式写真">
        <x-slot name="icon"><x-icons.store_image size="60" /></x-slot>
      </x-ui.top-item>

      <x-ui.top-item href="#" label="メニュー管理">
        <x-slot name="icon"><x-icons.menu size="60" /></x-slot>
      </x-ui.top-item>

      <x-ui.top-item href="#" label="閲覧数一覧">
        <x-slot name="icon"><x-icons.graph size="60" /></x-slot>
      </x-ui.top-item>

      <x-ui.top-item href="{{ route('store.reviews') }}" label="レビュー一覧">
        <x-slot name="icon"><x-icons.review size="60" stroke="1" dx="2" dy="2" /></x-slot>
      </x-ui.top-item>

      <x-ui.top-item href="#" label="予約状況">
        <x-slot name="icon"><x-icons.phone size="60" stroke="1" dx="2" dy="2" /></x-slot>
      </x-ui.top-item>
    </div>

  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('togglePublic');
    const text = document.getElementById('publicText');
    if (!toggle) return;

    toggle.addEventListener('change', async (e) => {
      const checked = e.target.checked;

      // 先に表示だけ即反映（体感よくなる）
      if (text) text.textContent = checked ? '（公開中）' : '（非公開）';

      try {
        const res = await fetch(@json(route('store.toggle-public')), {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': @json(csrf_token()),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ is_public: checked }),
        });

        if (!res.ok) throw new Error('Request failed');
      } catch (err) {
        // 失敗したら元に戻す
        e.target.checked = !checked;
        if (text) text.textContent = !checked ? '（公開中）' : '（非公開）';
        alert('保存に失敗したよ（通信エラー）');
      }
    });
  });
</script>
@endsection
