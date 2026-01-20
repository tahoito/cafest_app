@props(['review'])

@php
  // ユーザーアイコン
  $userIconPath = data_get($review,'user.icon_path', data_get($review,'icon_path'));
  $userIconUrl = null;
  $fallbackAvatar = asset('images/users/user1.jpg');

  if ($userIconPath) {
    $userIconUrl = str_starts_with($userIconPath, 'http')
      ? $userIconPath
      : asset(ltrim($userIconPath, '/'));
  }

  // レビュー画像
  $imagesRaw =
    data_get($review, 'images')
    ?? data_get($review, 'review_images')
    ?? data_get($review, 'photos')
    ?? [];

  $images = collect($imagesRaw)->map(function ($img) {
    $path = data_get($img, 'url', data_get($img, 'path', $img));
    if (!$path) return null;

    return str_starts_with($path, 'http')
      ? $path
      : asset(ltrim($path, '/'));
  })->filter()->values();

  // 安全に取る（Eloquentでも配列でもOK）
  $userName = (string) data_get($review, 'user.name', data_get($review, 'username', ''));
  $rating   = (float) data_get($review, 'rating', 0);
  $body     = (string) data_get($review, 'body', data_get($review, 'comment', ''));

  $date = data_get($review, 'created_at', data_get($review, 'date'));
  $dateText = '';
  if ($date) {
    try {
      $dateText = is_string($date) ? $date : $date->format('Y/m/d');
    } catch (\Throwable $e) {
      $dateText = (string) $date;
    }
  }

  $stars = max(0, min(5, (int) floor($rating + 0.00001)));
@endphp

<div {{ $attributes->merge([
    'class' => 'w-full max-w-[353px] rounded-xl bg-form ring-1 ring-black/5 shadow-[0_2px_10px_rgba(0,0,0,0.12)] text-left p-4 space-y-3'
  ]) }}>

  <div class="flex items-start justify-between">
    <div class="flex items-center gap-3">
      <img
        src="{{ $userIconUrl ?: $fallbackAvatar }}"
        class="h-10 w-10 rounded-full object-cover"
        alt=""
      >

      <div class="space-y-1">
        <div class="text-base font-medium text-text_color">
          {{ $userName }}
        </div>

        <div class="flex items-center gap-0.5">
          @for ($i = 1; $i <= 5; $i++)
            <x-icons.star class="h-4 w-4 {{ $i <= $stars ? 'text-star' : 'text-placeholder' }}" />
          @endfor
        </div>
      </div>
    </div>

    @if($dateText !== '')
      <div class="text-sm text-placeholder">
        {{ $dateText }}
      </div>
    @endif
  </div>

  <p class="text-base text-text_color leading-relaxed line-clamp-3">
    {{ $body }}
  </p>

  @if($images->count() > 0)
    <div class="flex gap-2">
      @foreach($images->take(2) as $i => $url)
        <div class="relative w-[125px] h-[125px] overflow-hidden rounded-xl">
          <img src="{{ $url }}" class="h-full w-full object-cover" alt="">

          @if($i === 1 && $images->count() > 2)
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white text-2xl font-semibold">
              +{{ $images->count() - 2 }}
            </div>
          @endif
        </div>
      @endforeach
    </div>
  @endif

</div>
