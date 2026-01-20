<div class="rounded-lg bg-form w-full max-w-[353px] shadow-[0_2px_10px_rgba(0,0,0,0.12)] p-4 space-y-3">

  <div class="flex items-start justify-between">
    <div class="flex items-center gap-3">
      <img
        src="{{ $review->user->avatar_url ?? '/images/user/avatar.png' }}"
        class="h-10 w-10 rounded-full object-cover"
      >

      <div class="space-y-1">
        <div class="text-base font-medium text-text_color">
          {{ $review->user->name }}
        </div>

        <div class="flex items-center gap-0.5">
          @for ($i = 1; $i <= 5; $i++)
            <x-icons.star
              class="h-4 w-4 {{ $i <= floor($review->rating) ? 'text-star' : 'text-placeholder' }}"
            />
          @endfor
        </div>
      </div>
    </div>

    <div class="text-sm text-placeholder">
      {{ $review->created_at->format('Y/m/d') }}
    </div>
  </div>

  {{-- 本文 --}}
  <p class="text-base text-text_color leading-relaxed line-clamp-3">
    {{ $review->body }}
  </p>

  {{-- 写真 --}}
  @if($review->images && $review->images->count() > 0)
    <div class="flex gap-2">
      @foreach($review->images->take(2) as $index => $image)
        <div class="relative w-[125px] h-[125px] overflow-hidden rounded-xl">
          <img src="{{ $image->url }}" class="h-full w-full object-cover">

          @if($index === 1 && $review->images->count() > 2)
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white text-2xl font-semibold">
              +{{ $review->images->count() - 2 }}
            </div>
          @endif
        </div>
      @endforeach
    </div>
  @endif

</div>
