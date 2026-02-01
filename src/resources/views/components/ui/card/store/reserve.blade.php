@props(['reservation'])

@php
  $name = (string) $reservation->name;

  $digits = preg_replace('/\D+/', '', (string) $reservation->phone);
  $prettyPhone = $digits;
  if (preg_match('/^\d{11}$/', $digits)) {
    $prettyPhone = preg_replace('/(\d{3})(\d{4})(\d{4})/', '$1-$2-$3', $digits);
  } elseif (preg_match('/^\d{10}$/', $digits)) {
    $prettyPhone = preg_replace('/(\d{2,4})(\d{2,4})(\d{4})/', '$1-$2-$3', $digits);
  }

  $start = \Carbon\Carbon::parse($reservation->start_at);
  $end   = \Carbon\Carbon::parse($reservation->end_at);

  $date = $start->format('Y/m/d');
  $timeText = $start->format('H:i') . '–' . $end->format('H:i');

  $party = (int) $reservation->party_size;
@endphp

<div class="w-[353px] max-w-full rounded-xl border border-main2 bg-form shadow-[0_4px_10px_rgba(0,0,0,0.18)] overflow-hidden"
     data-reservation-card>
  <div class="p-5">
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

    <div class="mt-2 text-base text-text_color tracking-wide">
      <span>{{ $date }}</span>
      <span class="ml-3">{{ $timeText }}</span>
    </div>

    <div class="mt-2 flex items-center justify-between">
      <div class="flex items-center gap-2 text-base text-text_color leading-none">
        <x-icons.number class="block shrink-0" size="30" />
        <span class="leading-none">{{ $party }}名</span>
     </div>

      <form method="POST"
            action="{{ route('store.reservation.visit', $reservation) }}"
            data-visit-form
            class="shrink-0">
        @csrf
        @method('PATCH')

        <button type="submit"
          class="h-11 px-6 rounded-full border border-main2 bg-base_color
                 text-text_color text-lg shadow-[0_2px_6px_rgba(0,0,0,0.18)]
                 active:scale-[0.98] transition">
          来店済みにする
        </button>
      </form>
    </div>
  </div>
</div>
