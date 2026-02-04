@props(['reservation'])

@php
  $name = (string) data_get($reservation, 'name', '');

  $digits = preg_replace('/\D+/', '', (string) data_get($reservation, 'phone', ''));
  $prettyPhone = $digits;

  if ($digits !== '') {
    if (preg_match('/^\d{11}$/', $digits)) {
      $prettyPhone = preg_replace('/(\d{3})(\d{4})(\d{4})/', '$1-$2-$3', $digits);
    } elseif (preg_match('/^\d{10}$/', $digits)) {
      $prettyPhone = preg_replace('/(\d{2,4})(\d{2,4})(\d{4})/', '$1-$2-$3', $digits);
    }
  }

  $start = \Carbon\Carbon::parse(data_get($reservation, 'start_at'));
  $end   = \Carbon\Carbon::parse(data_get($reservation, 'end_at'));

  $date = $start->format('Y/m/d');
  $timeText = $start->format('H:i') . '–' . $end->format('H:i');

  $party = (int) data_get($reservation, 'party_size', 0);
@endphp

<div
  data-reservation-card
  data-reservation-id="{{ (int) $reservation->id }}"
  class="w-[353px] max-w-full rounded-xl border border-main2 bg-form shadow-[0_4px_10px_rgba(0,0,0,0.18)] overflow-hidden"
>
  <div class="p-5 space-y-3">
    <div class="space-y-1">
      <div class="text-lg text-text_color">{{ $name }}様</div>

      @if($digits !== '')
        <a href="tel:{{ $digits }}" class="text-base text-placeholder tracking-wide">
          {{ $prettyPhone }}
        </a>
      @else
        <div class="text-base text-placeholder">電話番号なし</div>
      @endif
    </div>

    <div class="text-base text-text_color tracking-wide">
      <span>{{ $date }}</span>
      <span class="ml-3">{{ $timeText }}</span>
    </div>

    <div class="flex items-center justify-between gap-3">
      <div class="grid grid-cols-[30px_auto] items-center text-base text-text_color">
        <x-icons.number class="h-[30px] w-[30px] shrink-0 relative top-[2px]" />
        <span class="font-medium">{{ $party }}名</span>
      </div>

      <form
        method="POST"
        action="{{ route('store.reservation.visit', $reservation) }}"
        data-visit-form
        data-reservation-id="{{ (int) $reservation->id }}"
        class="shrink-0"
      >
        @csrf
        @method('PATCH')

        <button
          type="button"
          data-visit-open
          class="h-11 px-6 rounded-full border border-main2 bg-base_color
                 text-text_color text-lg shadow-[0_2px_6px_rgba(0,0,0,0.18)]
                 active:scale-[0.98] transition"
        >
          来店済みにする
        </button>
      </form>
    </div>
  </div>
</div>
