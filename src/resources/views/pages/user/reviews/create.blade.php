@extends('layouts.app')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color overflow-hidden">
  <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
    <div class="pt-[env(safe-area-inset-top)]">
      <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
        <a class="p-2" href="{{ url()->previous() }}">
          <x-icons.back class="w-5 h-5 text-text_color" />
        </a>

        <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
          レビュー投稿
        </h1>
      </div>
    </div>
  </header>

  <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
    <div class="w-full max-w-md mx-auto pt-4 space-y-5 pb-4 px-4">

      <section class="space-y-2 pb-3">
        <div class="text-xl text-text_color font-medium">{{ $store->name }}</div>
        @if($slideImage)
          <div class="relative w-full aspect-[16/10] overflow-hidden rounded-[8px]">
            <img
              src="{{ $slideImage->path }}"
              alt="{{ $store->name }}"
              class="w-full h-full object-cover"
            />
          </div>
        @endif
      </section>

      <form method="POST" action="{{ route('user.stores.reviews.store', $store) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- rating --}}
        <section class="space-y-2 pb-3">
          <input type="hidden" name="rating" id="rating" value="{{ old('rating', 0) }}">

          <div class="flex items-center gap-2">
            <div id="stars" class="flex" aria-label="rating">
              @for ($i = 1; $i <= 5; $i++)
                <button type="button" class="star p-0.5" data-value="{{ $i }}" aria-label="{{ $i }} star">
                  <x-icons.star class="w-8 h-8 text-placeholder" />
                </button>
              @endfor
            </div>

            <div class="text-sm text-text_color">
              <span id="ratingText"></span>
            </div>
          </div>

          @error('rating')
            <p class="text-sm text-red-500">{{ $message }}</p>
          @enderror
        </section>

        <section class="space-y-2 pb-2">
          <div class="text-lg text-text_color font-medium">本文</div>
          <textarea
            name="body"
            id="body"
            rows="6"
            class="w-full rounded-lg p-3 bg-form text-text_color text-base shadow-[0_1px_4px_rgba(0,0,0,0.20)] focus:outline-none focus:ring-1  focus:border-main"
            placeholder="お店の雰囲気やメニューについて入力"
          >{{ old('body') }}</textarea>

          @error('body')
            <p class="text-sm text-red-500">{{ $message }}</p>
          @enderror
        </section>

        <section class="space-y-1 pb-3">
          <div class="text-lg text-text_color font-medium">タグ選択</div>
          <div class="text-sm text-main">最大8個まで</div>

          <input type="hidden" name="tags" id="tags" value="{{ old('tags','') }}">

          <div id="tagArea" class="flex flex-wrap gap-2">
            @foreach(($approvedTags ?? collect()) as $tag)
              <button type="button" class="tag-btn" data-name="{{ $tag->name }}">
                <x-ui.tag class="!bg-base !border-main !text-text_color">
                  {{ $tag->name }}
                </x-ui.tag>
              </button>
            @endforeach
          </div>

          <div class="mt-3 space-y-2">
            <label class="text-sm text-text_color font-medium" for="tagInput">新しいタグを追加</label>
            <div class="flex gap-2">
              <input id="tagInput" type="text"
                class="w-full rounded-lg p-3 bg-form text-text_color text-base shadow-[0_1px_4px_rgba(0,0,0,0.20)] focus:outline-none focus:ring-1  focus:border-main"
                placeholder="例：集中できる, 席広い"
              />
              <button
                type="button"
                id="addTagButton"
                class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-main/10 transition"
                aria-label="タグを追加"
                >
                    <x-icons.plus class="text-main w-12 h-12 pt-3" />
                </button>
            </div>
          </div>

          @error('tags')
            <p class="text-sm text-red-500">{{ $message }}</p>
          @enderror
        </section>

        <section class="space-y-2 pb-3">
          <div class="text-lg text-text_color font-medium">写真</div>
          <div class="text-sm text-main">最大8枚まで</div>
          <input
            id="imagesInput"
            type="file"
            name="images[]"
            multiple
            accept="image/jpeg,image/png,image/webp"
            class="hidden"
          />

            <button type="button" id="imageTrigger" class="inline-flex items-center gap-2">
                <x-icons.add_image class="w-12 h-12 text-placeholder" />
                <span class="text-sm text-text_color/70">タップして追加</span>
            </button>

            <p id="imageCount" class="text-sm text-text_color hidden"></p>

            @error('images')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror
        </section>

        <div class="flex justify-center pt-8">
            <x-ui.button type="submit" class="w-full">
                投稿する
            </x-ui.button>
        </div>
      </form>

    </div>
  </div>
</div>


<script>
(function () {
  const ratingInput = document.getElementById('rating');
  const stars = Array.from(document.querySelectorAll('#stars .star'));
  const ratingText = document.getElementById('ratingText');

  function paint(val) {
    stars.forEach((btn) => {
      const v = Number(btn.dataset.value);
      const svg = btn.querySelector('svg');
      if (!svg) return;

      if (v <= val && val > 0) {
        svg.classList.add('text-star');
        svg.classList.remove('text-placeholder');
      } else {
        svg.classList.remove('text-star');
        svg.classList.add('text-placeholder');
      }
    });
    if (ratingText) {
    ratingText.textContent = val > 0 ? `${val} / 5` : `0 / 5`;
  }
  }

  paint(Number(ratingInput.value || 5));

  stars.forEach((btn) => {
    btn.addEventListener('click', () => {
      const val = Number(btn.dataset.value);
      ratingInput.value = String(val);
      paint(val);
    });
  });
})();
</script>


<script>
(function(){
  const hidden = document.getElementById('tags');
  const tagArea = document.getElementById('tagArea');
  const input = document.getElementById('tagInput');
  const addBtn = document.getElementById('addTagButton');

  if(!hidden || !tagArea || !input || !addBtn) return;

  const selected = new Set(
    (hidden.value || '').split(',').map(s => s.trim()).filter(Boolean)
  );

  function syncHidden(){
    hidden.value = Array.from(selected).slice(0, 8).join(',');
  }

  function setVisual(btn, on){
    const tagEl = btn.querySelector('*');
    if (!tagEl) return;

    if (on) {
      tagEl.classList.add('!bg-main','!border-main','!text-form');
      tagEl.classList.remove('!bg-base','!text-text_color');
    } else {
      tagEl.classList.remove('!bg-main','!text-form');
      tagEl.classList.add('!bg-base','!border-main','!text-text_color');
    }
  }

  function attachToggle(btn){
    btn.addEventListener('click', () => {
      const name = btn.dataset.name;
      if (!name) return;

      if (selected.has(name)) selected.delete(name);
      else {
        if (selected.size >= 8) return;
        selected.add(name);
      }

      setVisual(btn, selected.has(name));
      syncHidden();
    });
  }

  // 既存タグ（approved）にイベント付与
  Array.from(tagArea.querySelectorAll('.tag-btn')).forEach(btn => {
    setVisual(btn, selected.has(btn.dataset.name));
    attachToggle(btn);
  });

  function findButtonByName(name){
    return Array.from(tagArea.querySelectorAll('.tag-btn'))
      .find(b => (b.dataset.name || '') === name);
  }

  function createCustomChip(name){
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'tag-btn';
    btn.dataset.name = name;

    const chip = document.createElement('span');
    chip.className = 'inline-flex items-center justify-center rounded-full border border-main px-4 py-2 text-sm !bg-base !text-text_color';
    chip.textContent = name;

    btn.appendChild(chip);
    tagArea.appendChild(btn);

    setVisual(btn, true);
    attachToggle(btn);
  }

  function addCustom(){
    const raw = (input.value || '').trim();
    if(!raw) return;

    raw.split(',').map(s => s.trim()).filter(Boolean).forEach(name => {
      if (selected.size >= 8) return;

      const existing = findButtonByName(name);
      if (existing) {
        selected.add(name);
        setVisual(existing, true);
        return;
      }

      if (!selected.has(name)) {
        selected.add(name);
        createCustomChip(name);
      }
    });

    input.value = '';
    syncHidden();
  }

  addBtn.addEventListener('click', addCustom);
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      addCustom();
    }
  });

  syncHidden();
})();
</script>

<script>
(function(){
  const input = document.getElementById('imagesInput');
  const trigger = document.getElementById('imageTrigger');
  const count = document.getElementById('imageCount');

  if(!input || !trigger) return;

  trigger.addEventListener('click', () => input.click());

  input.addEventListener('change', () => {
    const n = input.files ? input.files.length : 0;

    if (n > 0) {
      trigger.classList.add('hidden');
      count.textContent = `${n}枚 選択中`;
      count.classList.remove('hidden');
    } else {
      trigger.classList.remove('hidden');
      count.classList.add('hidden');
      count.textContent = '';
    }
  });
})();
</script>
@endsection
