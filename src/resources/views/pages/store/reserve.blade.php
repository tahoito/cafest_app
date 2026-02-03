@extends('layouts.app')
@section('title','予約状況')

@section('hideNavbar')
@endsection

@section('content')
<div class="h-screen bg-base_color">
  <div class="h-full overflow-y-auto overscroll-contain">
    <header class="sticky top-0 z-50 bg-base_color">
      <div class="pt-[env(safe-area-inset-top)]">
        <div class="grid grid-cols-[48px_1fr_48px] items-center px-4 h-16">
          <a class="p-2" href="{{ route('store.top') }}">
            <x-icons.back class="w-5 h-5 text-text_color" />
          </a>

          <h1 class="text-center text-text_color text-2xl whitespace-nowrap overflow-hidden text-ellipsis">
            予約状況
          </h1>

          <div></div>
        </div>
      </div>
    </header>


    <div class="h-full overscroll-contain pt-[calc(env(safe-area-inset-top)+2rem)]">
        <div class="w-full max-w-md mx-auto space-y-5 pb-24">
            <div class="grid grid-cols-1 justify-items-center gap-5">
                @forelse($reservations as $reservation)
                    <x-ui.card.store.reserve
                        :reservation="$reservation" />
                @empty
                    <p class="text-center text-base text-placeholder py-10">
                    予約がまだありません
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div id="visitModal" class="fixed inset-0 z-[200] hidden">
  <div class="absolute inset-0 bg-black/40" data-visit-close></div>

  <div class="absolute left-1/2 top-1/2 w-[353px] -translate-x-1/2 -translate-y-1/2
              rounded-xl bg-base_color border border-main2 shadow-[0_6px_20px_rgba(0,0,0,0.25)] p-6">
    <div class="text-lg text-text_color text-center">
      来店済みにしますか？
    </div>

    <div class="mt-6 grid grid-cols-2 gap-3">
      <button type="button"
        class="h-11 rounded-full border border-main2 bg-base_color text-text_color
               shadow-[0_2px_6px_rgba(0,0,0,0.12)] active:scale-[0.98] transition"
        data-visit-close>
        キャンセル
      </button>

      <button type="button"
        class="h-11 rounded-full bg-main2 text-form
               shadow-[0_2px_6px_rgba(0,0,0,0.18)] active:scale-[0.98] transition"
        data-visit-ok>
        OK
      </button>
    </div>
  </div>
</div>

<script>
(() => {
  const modal = document.getElementById("visitModal");
  if (!modal) return;

  let targetForm = null;
  let targetCard = null;

  // 開く
  document.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-visit-open]");
    if (!btn) return;

    targetForm = btn.closest("[data-visit-form]");
    targetCard = btn.closest("[data-reservation-card]");

    modal.classList.remove("hidden");
  });

  // 閉じる（背景 / キャンセル）
  modal.addEventListener("click", (e) => {
    if (e.target.closest("[data-visit-close]")) {
      modal.classList.add("hidden");
      targetForm = null;
      targetCard = null;
    }
  });

  // OK
  const okBtn = modal.querySelector("[data-visit-ok]");
  if (!okBtn) return;

  okBtn.addEventListener("click", async () => {
    if (!targetForm) return;

    okBtn.disabled = true;

    try {
      const res = await fetch(targetForm.action, {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "Accept": "application/json",
          "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8",
        },
        body: new URLSearchParams(new FormData(targetForm)),
      });

      if (!res.ok) throw new Error("request failed");

      modal.classList.add("hidden");

      if (targetCard) {
        targetCard.style.transition = "opacity 180ms ease, transform 180ms ease";
        targetCard.style.opacity = "0";
        targetCard.style.transform = "scale(0.98)";
        setTimeout(() => targetCard.remove(), 200);
      }
    } catch (err) {
      alert("通信に失敗したよ。もう一回！");
    } finally {
      okBtn.disabled = false;
      targetForm = null;
      targetCard = null;
    }
  });
})();
</script>
@endsection 