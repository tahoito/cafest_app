@props([
  'name' => 'keyword',
  'placeholder' => 'カフェ名・エリアで検索',
  'action' => null,
  'method' => 'GET',
  'tag' => null,
])

@php
  // tags[] 優先。なければ tag 単体も吸収。
  $tagIds = (array) request('tags', []);

  if (!empty($tag)) {
    // $tag がモデルでも数値でもOK
    $tagIds[] = is_object($tag) ? ($tag->id ?? null) : $tag;
  }

  if (request()->filled('tag')) {
    $tagIds[] = request('tag');
  }

  $tagIds = array_values(array_unique(array_filter(array_map('intval', $tagIds))));
  $requestKeyword = (string) request($name, '');
  $prefillTagName = (string) data_get($tag, 'name', '');
  $prefillFromTag = $requestKeyword === '' && $prefillTagName !== '';
  $resolvedAction = $action;
  if (!$resolvedAction) {
    $resolvedAction = \Illuminate\Support\Facades\Route::has('user.search')
      ? route('user.search')
      : url()->current();
  }
@endphp


<form
  x-data="{
    keyword: @js($requestKeyword),
    prefill: @js($prefillTagName),
    fromTag: @js($prefillFromTag),
    dirty: false,
    init() {
      if (!this.fromTag) {
        $store.search.keyword = this.keyword;
      }
    },
    onInput(e) {
      this.dirty = true;
      this.keyword = e.target.value;
      $store.search.keyword = this.keyword;
    },
    submitSearch() {
      const external = document.getElementById('searchForm');
      if (external && typeof external.submit === 'function') {
        external.submit();
        return;
      }
      const selfForm = this.$refs?.form || this.$el;
      if (selfForm && typeof selfForm.submit === 'function') {
        selfForm.submit();
      }
    },
  }"
  action="{{ $resolvedAction }}"
  method="{{ $method }}"
  class="w-full"
  x-init="init()"
  @submit.prevent="submitSearch()"
  x-ref="form"
>
  @foreach($tagIds as $id)
    <input type="hidden" name="tags[]" value="{{ $id }}">
  @endforeach
  <div class="flex items-center gap-3 rounded-full bg-form px-4 py-3 shadow-[0_1px_4px_rgba(0,0,0,0.20)] ring-2 ring-main">
    <button type="submit" class="shrink-0" aria-label="Search">
      <x-icons.search class="h-6 w-6 text-placeholder text-text_color" />
    </button>

    <input
      type="search"
      name="{{ $name }}"
      value="{{ $prefillFromTag ? $prefillTagName : $requestKeyword }}"
      @input="onInput($event)"
      @keydown.enter.prevent="submitSearch()"
      placeholder="{{ $placeholder }}"
      class="w-full bg-transparent placeholder:text-placeholder focus:outline-none"
    />
  </div>
</form>
