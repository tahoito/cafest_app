@props([
  'href' => '#',
  'label' => '',
  'badge' => null,
])

<a href="{{ $href }}" 
   class="relative aspect-square w-[156px] h-[156px] rounded-2xl
        bg-accent border-2 border-main shadow-[0_3px_4px_rgba(0,0,0,0.25)]">

  @if($badge)
    <span class="absolute -top-2 -right-2 h-6 w-6
                grid place-items-center rounded-full
                bg-notification text-sm font-bold text-form">
      {{ $badge }}
    </span>
  @endif

  <div class="h-full w-full grid place-items-center text-text_color">
    <div class="flex flex-col items-center gap-2">

      <div class="h-[60px] w-[60px] grid place-items-center
                [&_svg]:block [&_svg]:shrink-0
                [&_svg]:stroke-current [&_svg]:fill-none">
        {{ $icon ?? '' }}
      </div>

      <div class="text-lg font-medium">
        {{ $label }}
      </div>
    </div>
  </div>
</a>
