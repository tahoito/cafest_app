<nav class="fixed bottom-0 left-0 w-full z-50"
     data-current="{{ request()->routeIs('user.search') ? 1 : (request()->routeIs('user.top') ? 0 : 0) }}">
  <div class="relative w-full">
    <svg
      id="waveSvg"
      class="pointer-events-none absolute -top-[54px] left-0 w-full h-[108px] text-main z-10 transform-gpu transition-transform duration-550 ease-[cubic-bezier(.22,1,.36,1)]"
      viewBox="0 -40 100 80"
      preserveAspectRatio="none"
      shape-rendering="geometricPrecision"
    >
      <path id="wavePath" fill="currentColor"></path>
    </svg>

    <!-- bar bg（フル幅） -->
    <div class="absolute inset-x-0 bottom-0 h-[78px] bg-main rounded-t-2xl z-0"></div>

    <!-- nav items -->
    <ul class="absolute inset-x-0 bottom-0 flex items-end justify-center gap-x-10 pb-2 z-30">
      @php
        $btnBase = "nav-item relative grid place-items-center";
        $btnSize = "h-14 w-14";
        $ease = "ease-[cubic-bezier(.22,1,.36,1)]";

        // 丸（waveより上に見せたいなら z-30 / waveより下にしたいなら z-20）
        $circleBase = "nav-circle absolute inset-0 rounded-full bg-transparent z-20 transform-gpu transition-transform duration-550 {$ease}";

        // アイコン
        $iconBase = "nav-icon absolute inset-0 z-30 grid place-items-center transform-gpu transition-transform duration-800 {$ease}";
      @endphp

      <li>
        <a href="{{ route('user.top') }}"
           class="{{ $btnBase }} {{ $btnSize }}"
           data-i="0">
          <span class="{{ $circleBase }}"></span>
          <span class="{{ $iconBase }}"><x-icons.home size="34" stroke="2.8" class="text-text_color" /></span>
        </a>
      </li>

      <li>
        <a href="{{ route('user.search') }}"
           class="{{ $btnBase }} {{ $btnSize }}"
           data-i="1">
          <span class="{{ $circleBase }}"></span>
          <span class="{{ $iconBase }}"><x-icons.search size="34" stroke="2.8" class="text-text_color" /></span>
        </a>
      </li>

      <li>
        <button class="{{ $btnBase }} {{ $btnSize }}" data-i="2" type="button">
          <span class="{{ $circleBase }}"></span>
          <span class="{{ $iconBase }}"><x-icons.reserve size="34" stroke="2.8" class="text-text_color" /></span>
        </button>
      </li>

      <li>
        <button class="{{ $btnBase }} {{ $btnSize }}" data-i="3" type="button">
          <span class="{{ $circleBase }}"></span>
          <span class="{{ $iconBase }}"><x-icons.mycafe size="34" stroke="2.8" class="text-text_color" /></span>
        </button>
      </li>
    </ul>
  </div>
</nav>

@push('scripts')
<script>
  const svg = document.getElementById("waveSvg");
  const path = document.getElementById("wavePath");
  const items = document.querySelectorAll(".nav-item");

  if (!svg || !path || !items || items.length === 0) {
    // ナビが無いページは何もしない
  } else {

    // ===== Active styles =====
    const activeBtnAdd = ["z-10"];
    const activeBtnRemove = ["z-0"];

    // ✅丸が「消えたように見える」対策で ring を追加
    const activeCircleAdd = ["bg-base_color", "-translate-y-5", "shadow-lg", "ring-1", "ring-black/10"];
    const activeCircleRemove = ["bg-transparent", "translate-y-0", "shadow-none", "ring-0", "ring-transparent"];

    const activeIconAdd = ["-translate-y-5"];
    const activeIconRemove = ["translate-y-0"];

    function setActive(index) {
      items.forEach((btn, i) => {
        const circle = btn.querySelector(".nav-circle");
        const icon   = btn.querySelector(".nav-icon");
        if (!circle || !icon) return;

        if (i === index) {
          btn.classList.add(...activeBtnAdd);
          btn.classList.remove(...activeBtnRemove);

          circle.classList.add(...activeCircleAdd);
          circle.classList.remove(...activeCircleRemove);

          icon.classList.add(...activeIconAdd);
          icon.classList.remove(...activeIconRemove);
        } else {
          btn.classList.remove(...activeBtnAdd);
          btn.classList.add(...activeBtnRemove);

          circle.classList.remove(...activeCircleAdd);
          circle.classList.add(...activeCircleRemove);

          icon.classList.remove(...activeIconAdd);
          icon.classList.add(...activeIconRemove);
        }
      });
    }

    // ===== icon draw animation =====
    function animateIconDraw(btn) {
      const svgEl = btn.querySelector("svg");
      if (!svgEl) return;

      svgEl.style.opacity = "0";
      svgEl.style.transform = "scale(0.92)";
      svgEl.style.transition =
        "opacity 420ms ease, transform 1600ms cubic-bezier(.22,1,.36,1)";

      requestAnimationFrame(() => {
        svgEl.style.opacity = "1";
        svgEl.style.transform = "scale(1)";
      });

      const els = svgEl.querySelectorAll("path, line, polyline, circle, rect");
      if (!els.length) return;

      els.forEach((el) => {
        try {
          const len = el.getTotalLength ? el.getTotalLength() : 0;

          el.style.transition = "none";
          el.style.strokeDasharray = `${len}`;
          el.style.strokeDashoffset = `${len}`;
          el.style.strokeLinecap = "round";
          el.style.strokeLinejoin = "round";

          void el.getBoundingClientRect();

          el.style.transition =
            "stroke-dashoffset 1600ms cubic-bezier(.22,1,.36,1)";

          el.style.strokeDashoffset = "0";
        } catch (e) {
          el.style.transition = "none";
          el.style.strokeDasharray = "200";
          el.style.strokeDashoffset = "200";
          void el.getBoundingClientRect();
          el.style.transition = "stroke-dashoffset 1600ms cubic-bezier(.22,1,.36,1)";
          el.style.strokeDashoffset = "0";
        }
      });
    }

    // ===== Wave =====
    const BASE_Y = 18;
    const BOTTOM = 28;

    const peakBase = 30;
    const peakMax  = 38;

    function wavePath(cx, peak, humpW) {
      const start = Math.max(0, cx - humpW);
      const end   = Math.min(100, cx + humpW);
      const h = humpW * 0.9;

      return `
        M 0 ${BOTTOM}
        L 0 ${BASE_Y}
        L ${start} ${BASE_Y}
        C ${start + h} ${BASE_Y}, ${cx - h} ${peak}, ${cx} ${peak}
        C ${cx + h} ${peak}, ${end - h} ${BASE_Y}, ${end} ${BASE_Y}
        L 100 ${BASE_Y}
        L 100 ${BOTTOM}
        Z
      `;
    }

    function calcCx(btn) {
      if (!btn) return 50;

      const svgRect = svg.getBoundingClientRect();
      const btnRect = btn.getBoundingClientRect();
      const centerX = (btnRect.left + btnRect.width / 2) - svgRect.left;

      if (!svgRect.width) return 50;
      return (centerX / svgRect.width) * 100;
    }

    let currentCx = null;
    let targetCx = null;
    let rafId = null;

    function moveWaveTo(cx) {
      targetCx = cx;
      if (currentCx == null) currentCx = cx;

      cancelAnimationFrame(rafId);

      const duration = 480;
      const startTime = performance.now();

      const humpBase = 22;
      const humpMax  = 28;

      const tick = (t) => {
        const p = Math.min((t - startTime) / duration, 1);

        currentCx += (targetCx - currentCx) * 0.24;

        const bounce = Math.sin(p * Math.PI); // 0→1→0
        const peak = peakBase + (peakMax - peakBase) * bounce;
        const humpW = humpBase + (humpMax - humpBase) * bounce;

        path.setAttribute("d", wavePath(currentCx, peak, humpW));

        if (Math.abs(targetCx - currentCx) > 0.08 || p < 1) {
          rafId = requestAnimationFrame(tick);
        }
      };

      rafId = requestAnimationFrame(tick);
    }

    // ===== Init（1回だけ）=====
    const navEl = document.querySelector("nav[data-current]");
    const start = navEl ? Number(navEl.dataset.current) : 0;

    setActive(start);
    animateIconDraw(items[start]);

    const initialCx = calcCx(items[start]);
    currentCx = initialCx;
    targetCx = initialCx;
    path.setAttribute("d", wavePath(initialCx, peakBase, 22));

    // ===== Click =====
    items.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        const i = Number(btn.dataset.i);

        setActive(i);
        animateIconDraw(btn);
        moveWaveTo(calcCx(btn));

        if (btn.tagName === "A") {
          e.preventDefault();
          const href = btn.href;
          setTimeout(() => {
            window.location.href = href;
          }, 120);
        }
      });
    });

    // ===== Resize =====
    window.addEventListener("resize", () => {
      const activeBtn =
        [...items].find(b => b?.querySelector(".nav-circle")?.classList.contains("bg-base_color"))
        || items[0];

      const cx = calcCx(activeBtn);
      currentCx = cx;
      targetCx = cx;
      path.setAttribute("d", wavePath(cx, peakBase, 22));
    });
  }
</script>
@endpush
