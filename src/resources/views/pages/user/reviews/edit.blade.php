@extends('layouts.app')
@section('title','レビュー編集')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color overflow-hidden">
  {{-- Header --}}
  <header class="fixed top-0 inset-x-0 z-50 bg-base_color">
    <div class="pt-[env(safe-area-inset-top)]">
      <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
        <a class="p-2" href="{{ route('user.mycafe') }}">
          <x-icons.back class="w-5 h-5 text-text_color" />
        </a>

        <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
          {{ $review->store->name ?? 'レビュー編集' }}
        </h1>

        <div></div>
      </div>
    </div>
  </header>

  @php 
    $iconPath = $review->user->icon_path ?? null;
    $iconSrc = $iconPath 
      ? Storage::url($iconPath)
      : asset('/users/user1.jpg')
  @endphp

  {{-- Body --}}
  <div class="h-full overflow-y-auto overscroll-contain pt-[calc(env(safe-area-inset-top)+4rem)]">
    <div class="w-full max-w-md mx-auto px-4 pt-4 space-y-6 pb-24">

      
      <form method="POST"
        action="{{ route('user.stores.reviews.update', [$store, $review]) }}"
        enctype="multipart/form-data"
      >
        @csrf
        @method('PUT')


        {{-- ユーザー --}}
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <img
              src="{{ $iconSrc }}"
              alt=""
              class="w-10 h-10 rounded-full object-cover bg-form"
            >
            <span class="text-lg text-text_color">
              {{ $review->user->username ?? 'cafest_vantan' }}
            </span>
          </div>

          <span class="text-sm text-placeholder">
            {{ $review->created_at->format('Y/m/d') }}
          </span>
        </div>

        {{-- rating --}}
        <section class="space-y-5 pb-3">
          <input type="hidden" name="rating" id="rating" value="{{ old('rating', $review->rating) }}">

          <div class="flex items-center gap-1">
            <div id="stars" class="flex" aria-label="rating">
              @for ($i = 1; $i <= 5; $i++)
                <button type="button" class="star p-0.5" data-value="{{ $i }}" aria-label="{{ $i }} star">
                  <x-icons.star class="w-7 h-7 text-placeholder" />
                </button>
              @endfor
            </div>
          </div>
        </section>

        {{-- 本文 --}}
        <section class="space-y-2 pb-3">
          <div class="text-lg text-text_color font-medium">本文</div>

          <textarea
            name="body"
            id="body"
            rows="6"
            class="w-full rounded-lg p-3 bg-form text-text_color text-base shadow-[0_1px_4px_rgba(0,0,0,0.20)] focus:outline-none focus:ring-1 focus:border-main"
            placeholder="お店の雰囲気やメニューについて入力"
          >{{ old('body', $review->body) }}</textarea>

          @error('body')
            <p class="text-sm text-red-500">{{ $message }}</p>
          @enderror
        </section>

        {{-- タグ --}}
        <section class="space-y-2 pb-4">
          <div class="text-lg text-text_color font-medium">タグ</div>
          <div class="text-sm text-main">最大8個まで</div>

          <div id="tagArea" class="flex flex-wrap gap-2">
            @foreach(($approvedTags ?? collect()) as $tag)
              @php
                $checked = in_array($tag->id, old('tag_ids', $review->tags->pluck('id')->all()));
              @endphp

              <div class="tag-item cursor-pointer" data-id="{{ $tag->id }}">
                <input
                  id="tag_{{ $tag->id }}"
                  type="checkbox"
                  name="tag_ids[]"
                  value="{{ $tag->id }}"
                  class="tag-checkbox sr-only"
                  {{ $checked ? 'checked' : '' }}
                >

                <button
                  type="button"
                  class="tag-chip inline-flex items-center justify-center whitespace-nowrap rounded-full border px-[16px] py-[2px] text-sm transition select-none
                    {{ $checked ? 'bg-main border-main text-form' : 'bg-base_color border-main text-text_color' }}"
                >
                  {{ $tag->name }}
                </button>
              </div>
            @endforeach
          </div>

          <div id="newTagInputs"></div>
          <div class="mt-4 space-y-2">
            <label class="text-sm text-text_color font-medium" for="tagInput">新しいタグを追加</label>
            <div class="flex gap-2">
              <input
                id="tagInput"
                type="text"
                class="w-full rounded-lg p-3 bg-form text-text_color text-base shadow-[0_1px_4px_rgba(0,0,0,0.20)] focus:outline-none focus:ring-1 focus:border-main"
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

        {{-- 写真 --}}
        <section class="space-y-2 pb-4">
          <div class="text-lg text-text_color font-medium">写真</div>
          <div class="text-sm text-main">最大8枚まで</div>

          {{-- 既存画像 --}}
          @if($review->images->count())
            <div class="space-y-3">
              @foreach($review->images as $img)
                <label class="block">
                  <div class="w-full aspect-[16/10] overflow-hidden rounded-xl bg-form shadow-[0_2px_6px_rgba(0,0,0,0.15)]">
                    <img src="{{ Storage::url($img->path) }}" class="w-full h-full object-cover" alt="">
                  </div>

                  <div class="mt-2 flex items-center gap-2">
                    <input type="checkbox" name="delete_image_ids[]" value="{{ $img->id }}">
                    <span class="text-sm text-text_color">この写真を削除</span>
                  </div>
                </label>
              @endforeach
            </div>
          @endif

          {{-- 追加 --}}
          <div id="imageInputs"></div>

          <button type="button" id="imageTrigger" class="inline-flex items-center gap-2">
            <x-icons.add_image class="w-12 h-12 text-placeholder" />
            <span class="text-sm text-text_color/70">タップして追加</span>
          </button>

          <p id="imageCount" class="text-sm text-text_color hidden"></p>
          <div id="imagePreview" class="mt-3 space-y-3"></div>

          @error('images')
            <p class="text-sm text-red-500">{{ $message }}</p>
          @enderror
          @error('images.*')
            <p class="text-sm text-red-500">{{ $message }}</p>
          @enderror
        </section>

        {{-- 保存 --}}
        <x-ui.button type="submit" class="w-full">
          保存する
        </x-ui.button>
      </form>

      <form method="POST" action="{{ route('user.stores.reviews.destroy', [$store, $review]) }}">
        @csrf
        @method('DELETE')

        <button
          type="submit"
          class="mx-auto block h-12 w-full rounded-full border-2 border-main bg-base text-[18px] text-text_color shadow-[0_4px_10px_rgba(0,0,0,0.18)]"
        >
          削除する
        </button>
      </form>

    </div>
  </div>
</div>

{{-- ⭐ rating --}}
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

  paint(Number(ratingInput?.value || 0));

  stars.forEach((btn) => {
    btn.addEventListener('click', () => {
      const val = Number(btn.dataset.value);
      ratingInput.value = String(val);
      paint(val);
    });
  });
})();
</script>

{{-- 🏷 tags --}}
<script>
(function(){
  const area = document.getElementById('tagArea');
  if(!area) return;

  const MAX = 8;

  function selectedCount(){
    return area.querySelectorAll('.tag-checkbox:checked').length;
  }

  function paint(item){
    const cb = item.querySelector('.tag-checkbox');
    const chip = item.querySelector('.tag-chip');
    if(!cb || !chip) return;

    if(cb.checked){
      chip.classList.add('bg-main','border-main','text-form');
      chip.classList.remove('bg-base_color','text-text_color');
    }else{
      chip.classList.remove('bg-main','text-form');
      chip.classList.add('bg-base_color','text-text_color');
    }
  }

  // 初期反映
  area.querySelectorAll('.tag-item').forEach(paint);

  area.addEventListener('click', (e) => {
    const chip = e.target.closest('.tag-chip');
    if(!chip) return;

    const item = chip.closest('.tag-item');
    if(!item) return;

    const cb = item.querySelector('.tag-checkbox');
    if(!cb) return;

    // 反転
    cb.checked = !cb.checked;

    // 8個制限
    if(cb.checked && selectedCount() > MAX){
      cb.checked = false;
    }

    paint(item);
  });
})();
</script>


<script>
(function(){
  const input = document.getElementById('tagInput');
  const addBtn = document.getElementById('addTagButton');
  const wrap = document.getElementById('newTagInputs');
  if(!input || !addBtn || !wrap) return;

  function currentNewNames(){
    return Array.from(wrap.querySelectorAll('input[name="new_tags[]"]'))
      .map(i => i.value.trim())
      .filter(Boolean);
  }

  function addTag(name){
    const v = (name ?? '').trim();
    if(!v) return;

    // 重複防止（すでに追加済みなら無視）
    if(currentNewNames().includes(v)) return;

    // hidden input
    const hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'new_tags[]';
    hidden.value = v;

    // 見た目チップ（クリックで削除）
    const chip = document.createElement('button');
    chip.type = 'button';
    chip.className = 'inline-flex items-center gap-1 px-3 py-1 rounded-full border border-main bg-main text-form text-sm';
    chip.textContent = v;

    chip.addEventListener('click', () => {
      hidden.remove();
      chip.remove();
    });

    wrap.appendChild(hidden);
    wrap.appendChild(chip);
  }

  addBtn.addEventListener('click', () => {
    addTag(input.value);
    input.value = '';
    input.focus();
  });

  input.addEventListener('keydown', (e) => {
    if(e.key === 'Enter'){
      e.preventDefault();
      addTag(input.value);
      input.value = '';
    }
  });
})();
</script>



{{-- 🖼 images --}}
<script>
(function(){
  const inputsWrap = document.getElementById('imageInputs');
  const trigger = document.getElementById('imageTrigger');
  const count = document.getElementById('imageCount');
  const preview = document.getElementById('imagePreview');
  const triggerText = trigger?.querySelector('span');

  if(!inputsWrap || !trigger || !preview) return;

  const MAX = 8;

  // 既存画像枚数（削除チェックはJSでは考慮しない。サーバ側で最終調整）
  const EXIST = {{ (int)($review->images->count() ?? 0) }};
  const LIMIT = Math.max(0, MAX - EXIST);

  let activeInput = null;

  function makeInput(){
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'images[]';
    input.accept = 'image/jpeg,image/png,image/webp';
    input.className = 'hidden';
    input.multiple = true;

    input.addEventListener('change', () => {
      activeInput = makeInput();
      render();
    });

    inputsWrap.appendChild(input);
    return input;
  }

  activeInput = makeInput();

  trigger.addEventListener('click', () => {
    if(LIMIT <= 0) return;
    if(activeInput) activeInput.click();
  });

  function clearPreview(){
    preview.querySelectorAll('img[data-blob-url="1"]').forEach(img => {
      URL.revokeObjectURL(img.src);
    });
    preview.innerHTML = '';
  }

  function allFiles(){
    const inputs = Array.from(inputsWrap.querySelectorAll('input[type="file"]'));
    const files = [];
    inputs.forEach(inp => {
      Array.from(inp.files || []).forEach(f => files.push({ file: f, input: inp }));
    });
    return files;
  }

  function render(){
    clearPreview();

    const items = allFiles();
    const total = items.length;

    if(LIMIT <= 0){
      if(triggerText) triggerText.textContent = '追加できません（8枚まで）';
      if(count){
        count.textContent = `既存${EXIST}枚 / 最大8枚`;
        count.classList.remove('hidden');
      }
      return;
    }

    if(total === 0){
      if(count){
        count.classList.add('hidden');
        count.textContent = '';
      }
      if(triggerText) triggerText.textContent = 'タップして追加';
      return;
    }

    if(total >= LIMIT){
      if(triggerText) triggerText.textContent = `最大${LIMIT}枚まで追加OK`;
    } else {
      if(triggerText) triggerText.textContent = '写真を追加/変更';
    }

    if(count){
      count.textContent = `追加${Math.min(total, LIMIT)}枚（残り${Math.max(0, LIMIT - total)}枚）`;
      count.classList.remove('hidden');
    }

    items.slice(0, LIMIT).forEach(({file}) => {
      const url = URL.createObjectURL(file);

      const wrap = document.createElement('div');
      wrap.className =
        'w-full aspect-[16/10] overflow-hidden rounded-xl bg-form ' +
        'shadow-[0_2px_6px_rgba(0,0,0,0.15)]';

      const img = document.createElement('img');
      img.src = url;
      img.alt = 'selected image';
      img.className = 'w-full h-full object-cover';
      img.dataset.blobUrl = '1';

      wrap.appendChild(img);
      preview.appendChild(wrap);
    });
  }
})();
</script>

@endsection
