@props([
  'name' => 'keyword',
  'placeholder' => 'カフェ名・エリアで検索',
  'action' => null,
  'method' => 'GET',
  'tag' => null,
])

<form action="{{ $action ?? url()->current() }}" method="{{ $method }}" class="w-full">
  <div class="flex items-center gap-3 rounded-full bg-form px-4 py-3 shadow-[0_1px_4px_rgba(0,0,0,0.20)] ring-2 ring-main">
    <button type="submit" class="shrink-0" aria-label="Search">
      <x-icons.search class="h-6 w-6 text-placeholder text-text_color" />
    </button>

    <input
      type="search"
      name="{{ $name }}"
      value="{{ request($name) ?: ($tag?->name ?? '') }}"
      x-init="$store.search.keyword = $el.value"
      x-model="$store.search.keyword"
      placeholder="{{ $placeholder }}"
      class="w-full bg-transparent placeholder:text-placeholder focus:outline-none"
    />
  </div>
</form>
