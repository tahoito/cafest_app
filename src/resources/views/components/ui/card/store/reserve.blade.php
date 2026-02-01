@props([
    'reservation',
])

@php
  $name  = $reservation->user_name ?? $reservation->user?->username ?? 'お客様';
  $phone = $reservation->user_phone ?? $reservation->user?->phone ?? '';
  $date  = \Carbon\Carbon::parse($reservation->date)->format('Y/m/d'); // date がCarbonならそのままformatでOK
  $start = \Carbon\Carbon::parse($reservation->start_time)->format('H:i');
  $end   = \Carbon\Carbon::parse($reservation->end_time)->format('H:i');
  $count = (int)($reservation->people_count ?? 1);


  $visited = (bool)($reservation->visited_at ?? $reservation->status === 'visited');
@endphp

<div class="w-full rounded-xl border border-main2 bg-form bg-white shadow-[0_4px_10px_rgba(0,0,0,0.18)] overflow-hidden"
    data-reservation-card>
    <div class="p-5">
        <div class="space-y-1">
            <div class="text-lg text-text_color">
                {{ $name }}様
            </div>

            @if($phone)
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}"
                class="text-base text-text_color tracking-wide">
                {{ $phone }}
            </a>
            @else 
            <div class="text-base text-placeholder">電話番号なし</div>
            @endif 
        </div>

        <div class="mt-5 text-base text-text_color tracking-wide">
            <span class="font-medium">{{ $date }}</span>
            <span class="ml-5 font-medium">{{ $start }}-{{ $end }}</span>
        </div>

        <div class="mt-5 flex items-center justify-between">
            <div class="flex items-center gap-2 text-base text-text_color">
                <x-icons.people class="w-6 h-6" />
                <span class="font-medium">{{ $count }}名</span>
            </div>

            
            <form method="POST" action="{{ route('store.reservations.visit', $reservation) }}"
                data-visit-form 
                class="shrink-0">
                @csrf 
                @method('PATCH')

                <button type="submit" class="h-11 px-6 rounded-full border border-main2 bg-base_color
                    text-text_color text-lg shadow-[0_2px_6px_rgba(0,0,0,0.18)]
                   active:scale-[0.98] transition">来店済みにする
                </button>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
  document.addEventListener('submit', async (e) => {
    const form = e.target;
    if (!(form instanceof HTMLFormElement)) return;
    if (!form.matches('[data-visit-form]')) return;

    e.preventDefault();

    const card = form.closest('[data-reservation-card]');
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn?.textContent;

    // UI：即時フィードバック
    if (btn) {
      btn.disabled = true;
      btn.textContent = '処理中...';
    }
    if (card) {
      card.style.transition = 'opacity 180ms ease, transform 180ms ease';
      card.style.opacity = '0.35';
      card.style.transform = 'scale(0.98)';
    }

    try {
      const res = await fetch(form.action, {
        method: 'POST', // LaravelはPOST + _methodでPATCHにするのが楽
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': form.querySelector('input[name="_token"]')?.value ?? '',
          'Accept': 'application/json',
          'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
        },
        body: new URLSearchParams(new FormData(form)),
      });

      if (!res.ok) {
        // 422/403/500など
        throw new Error('request failed');
      }

      // 成功：フェードアウトしてからDOMから消す
      if (card) {
        card.style.opacity = '0';
        card.style.transform = 'scale(0.96)';
        setTimeout(() => card.remove(), 220);
      }
    } catch (err) {
      // 失敗：戻す
      if (btn) {
        btn.disabled = false;
        btn.textContent = originalText ?? '来店済みにする';
      }
      if (card) {
        card.style.opacity = '1';
        card.style.transform = 'scale(1)';
      }
      alert('通信に失敗したよ。もう一回試してみて！');
    }
  });
})();
</script>
