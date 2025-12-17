@props([
  'value' => '',
  'name' => 'q',
  'placeholder' => 'カフェ名・エリアで検索',
  'action' => null,
  'method' => 'GET',
])

<form action="{{ $action ?? url()->current() }}" method="{{ $method }}" class="w-full">
  <div class="flex items-center gap-3 rounded-full bg-form px-4 py-3 shadow-sm ring-2 ring-main">
    <button type="submit" class="shrink-0" aria-label="Search">
      <x-icons.search class="h-6 w-6 text-placeholder text-text" />
    </button>

    <input
      type="text"
      name="{{ $name }}"
      value="{{ old($name, $value) }}"
      placeholder="{{ $placeholder }}"
      class="w-full bg-transparent placeholder:text-placeholder focus:outline-none"
    />
  </div>
</form>
