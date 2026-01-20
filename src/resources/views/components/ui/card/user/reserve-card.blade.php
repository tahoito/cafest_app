@props([
  'reservation' => null,
  'href' => null,
  'image' => null,
  'shopName' => null,
  'date' => null,
  'time' => null,
  'people' => null,
  'onCancel' => null,
])

@php
  use Illuminate\Support\Facades\Storage;

  $defaultCard = Storage::url('images/store/card.png');
  $store = data_get($reservation, 'store');

  $shopName = $shopName
    ?? (string) data_get($reservation, 'shop_name', data_get($store, 'name', 'cafest'));

  $slide0 = data_get($store, 'slideImages.0');
  $slideUrl = data_get($slide0, 'url') ?? data_get($slide0, 'path') ?? data_get($slide0, 'image_url');

  $imageUrl = (string) (
    $image
    ?? $slideUrl
    ?? data_get($store, 'image_url')
    ?? data_get($reservation, 'image_url')
    ?? ''
  );

  $startAt = data_get($reservation, 'start_at');
  $endAt   = data_get($reservation, 'end_at');

  try { $startAt = $startAt ? \Carbon\Carbon::parse($startAt) : null; } catch (\Throwable $e) { $startAt = null; }
  try { $endAt   = $endAt ? \Carbon\Carbon::parse($endAt) : null; } catch (\Throwable $e) { $endAt = null; }

  $dateText = $date ?? ($startAt ? $startAt->format('Y/m/d') : (string) data_get($reservation, 'date', ''));
  $timeText = $time ?? (($startAt && $endAt) ? $startAt->format('H:i').'-'.$endAt->format('H:i') : (string) data_get($reservation, 'time', ''));

  $peopleText = $people ?? (string) data_get($reservation, 'party_size', data_get($reservation, 'people', ''));
  if ($peopleText !== '' && !str_contains($peopleText, '名')) $peopleText .= '名';

  $cancelAction = $onCancel ?: (data_get($reservation,'id') ? route('user.reserve.destroy', data_get($reservation,'id')) : '#');
@endphp

<div x-data="{ confirmOpen: false }" class="w-[344px] rounded-lg border border-line bg-form p-5 shadow-[0_2px_10px_rgba(0,0,0,0.12)]">
  <div class="flex gap-5">
    <div class="shrink-0">
      <div class="h-[130px] w-[130px] overflow-hidden rounded-lg bg-base ring-1 ring-black/5">
        <img
          src="{{ $imageUrl !== '' ? $imageUrl : $defaultCard }}"
          alt=""
          class="h-full w-full object-cover"
          loading="lazy"
        />
      </div>
    </div>

    <div class="min-w-0 flex-1">
      <div class="flex items-center gap-1">
        <x-icons.store stroke="1.5" class="h-6 w-6 text-text_color" />
        <div class="truncate text-xl text-text_color">
          {{ $shopName }}
        </div>
      </div>

      <div class="mt-1 text-sm text-main">予約情報</div>

      <div class="mt-1 grid grid-cols-[28px_1fr] gap-x-[2px] gap-y-[3px] text-base text-text_color">
        <x-icons.date class="h-6 w-6 place-self-center text-text_color" />
        <span class="leading-none">{{ $dateText }}</span>

        <x-icons.time class="h-6 w-6 place-self-center text-text_color" />
        <span class="leading-none">{{ $timeText }}</span>

        <x-icons.number class="h-6 w-6 place-self-center text-text_color" />
        <span class="leading-none">{{ $peopleText }}</span>
      </div>
    </div>
  </div>


  <div class="mt-3 flex justify-center">
    <button
      type="button"
      class="mx-auto block h-12 w-[260px]
             rounded-full border-2 border-main bg-base
             text-sm text-text_color
             shadow-[0_4px_10px_rgba(0,0,0,0.18)]"
      @click="confirmOpen = true"
    >
      キャンセルする
    </button>
  </div>

  <div
    x-show="confirmOpen"
    x-transition.opacity
    class="fixed inset-0 z-[300] flex items-center justify-center"
    style="display:none;"
    @keydown.escape.window="confirmOpen = false"
  >
    <div class="absolute inset-0 bg-black/40" @click="confirmOpen = false"></div>

    <div class="relative w-[353px] rounded-lg bg-base_color px-6 py-6 shadow-[0_10px_30px_rgba(0,0,0,0.25)]" @click.stop>
      <button
        type="button"
        class="absolute left-3 top-3 grid h-10 w-10 place-items-center rounded-full hover:bg-black/5 active:scale-95"
        @click="confirmOpen = false"
        aria-label="閉じる"
      >
        <x-icons.close class="h-7 w-7 text-text_color" />
      </button>

      <div class="text-center pt-4">
        <div class="text-base text-text_color">
          本当にキャンセルしますか？
        </div>

        <div class="mt-5 space-y-3">
          <form method="POST" action="{{ $cancelAction }}">
            @csrf
            @method('DELETE')

            <x-ui.button type="submit" variant="secondary" class="w-full text-form">
              キャンセルする
            </x-ui.button>
          </form>

          <button
            type="button"
            class="w-full text-sm text-text_color"
            @click="confirmOpen = false"
          >
            やめる
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
