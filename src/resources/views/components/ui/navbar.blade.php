<nav class="fixed bottom-0 left-0 w-full z-50">
  <div class="relative mx-auto max-w-md px-12 pt-6 pb-8">

    <!-- wave -->
    <svg
      id="waveSvg"
      class="pointer-events-none absolute -top-[50px] left-0 w-full h-[100px] text-main"
      viewBox="0 -28 100 56"
      preserveAspectRatio="none"
      shape-rendering="geometricPrecision"
    >
      <path id="wavePath" fill="currentColor"></path>
    </svg>

    <!-- bar bg -->
    <div class="absolute inset-x-0 bottom-0 h-[78px] bg-main rounded-t-2xl"></div>

    <!-- nav items -->
    <ul class="absolute inset-x-0 bottom-0 flex items-end justify-center px-16 pb-5 gap-x-10">
      @php
        // 透明の器（クリック領域）
        $btnBase = "nav-item relative grid place-items-center";
        $btnSize = "h-14 w-14";

        // ぬるっと上がる easing（iOSっぽい）
        $ease = "ease-[cubic-bezier(.22,1,.36,1)]";

        // 白丸（Figmaのbg-base）
        $circleBase = "nav-circle absolute inset-0 rounded-full bg-transparent transform-gpu transition-transform duration-550 {$ease}";

        // アイコン（白丸と同じだけ上げる）
        $iconBase = "nav-icon relative z-10 grid place-items-center transform-gpu transition-transform duration-550 {$ease}";
      @endphp

      <li>
        <button class="{{ $btnBase }} {{ $btnSize }}" data-i="0" type="button" aria-label="home">
          <span class="{{ $circleBase }}"></span>
          <span class="{{ $iconBase }}">
            <x-icons.home size="24" stroke="1.6" class="text-text" />
          </span>
        </button>
      </li>

      <li>
        <button class="{{ $btnBase }} {{ $btnSize }}" data-i="1" type="button" aria-label="search">
          <span class="{{ $circleBase }}"></span>
          <span class="{{ $iconBase }}">
            <x-icons.search size="24" stroke="1.6" class="text-text" />
          </span>
        </button>
      </li>

      <li>
        <button class="{{ $btnBase }} {{ $btnSize }}" data-i="2" type="button" aria-label="reserve">
          <span class="{{ $circleBase }}"></span>
          <span class="{{ $iconBase }}">
            <x-icons.reserve size="24" stroke="1.6" class="text-text" />
          </span>
        </button>
      </li>

      <li>
        <button class="{{ $btnBase }} {{ $btnSize }}" data-i="3" type="button" aria-label="mycafe">
          <span class="{{ $circleBase }}"></span>
          <span class="{{ $iconBase }}">
            <x-icons.mycafe size="24" stroke="1.6" class="text-text" />
          </span>
        </button>
      </li>
    </ul>
  </div>
</nav>

@once
<script>
  const svg = document.getElementById("waveSvg");
  const path = document.getElementById("wavePath");
  const items = document.querySelectorAll(".nav-item");

  // ===== Active styles =====
  // ボタン自体は前に出すだけ（白丸・アイコンは中身を動かす）
  const activeBtnAdd = ["z-10"];
  const activeBtnRemove = ["z-0"];

  // ★たほ環境の白：bg-base_color
  const activeCircleAdd = ["bg-base_color", "-translate-y-5", "shadow-lg"];
  const activeCircleRemove = ["bg-transparent", "translate-y-0", "shadow-none"];

  // ★アイコンも同じだけ上げる（中央に固定）
  const activeIconAdd = ["-translate-y-5"];
  const activeIconRemove = ["translate-y-0"];

  function setActive(index) {
    items.forEach((btn, i) => {
      const circle = btn.querySelector(".nav-circle");
      const icon   = btn.querySelector(".nav-icon");

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

  // ===== Wave =====
  // viewBox: 0 -28 100 56
  const BASE_Y = 18;
  const BOTTOM = 28;

  function wavePath(cx, peak) {
    const humpW = 22;              // 横幅
    const start = Math.max(0, cx - humpW);
    const end   = Math.min(100, cx + humpW);
    const h = humpW * 0.9;         // なめらかさ

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
    const svgRect = svg.getBoundingClientRect();
    const btnRect = btn.getBoundingClientRect();
    const centerX = (btnRect.left + btnRect.width / 2) - svgRect.left;
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

    // 高低差（深め）
    const peakBase = -18;
    const peakMin  = -28;

    const tick = (t) => {
      const p = Math.min((t - startTime) / duration, 1);

      // 追従
      currentCx += (targetCx - currentCx) * 0.24;

      // ぷるん（深く→戻る）
      const bounce = Math.sin(p * Math.PI);
      const peak = peakBase + (peakMin - peakBase) * bounce;

      path.setAttribute("d", wavePath(currentCx, peak));

      if (Math.abs(targetCx - currentCx) > 0.08 || p < 1) {
        rafId = requestAnimationFrame(tick);
      }
    };

    rafId = requestAnimationFrame(tick);
  }

  // ===== Init =====
  setActive(0);
  const initialCx = calcCx(items[0]);
  currentCx = initialCx;
  path.setAttribute("d", wavePath(initialCx, -18));

  // Click
  items.forEach((btn) => {
    btn.addEventListener("click", () => {
      const i = Number(btn.dataset.i);
      setActive(i);
      moveWaveTo(calcCx(btn));
    });
  });

  // Resize
  window.addEventListener("resize", () => {
    const activeBtn =
      [...items].find(b => b.querySelector(".nav-circle")?.classList.contains("bg-base_color")) || items[0];

    const cx = calcCx(activeBtn);
    currentCx = cx;
    targetCx = cx;
    path.setAttribute("d", wavePath(cx, -18));
  });
</script>
@endonce
