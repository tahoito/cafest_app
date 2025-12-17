<nav class="fixed bottom-0 left-0 w-full bg-main z-50">
  <div class="relative mx-auto max-w-md px-12 pt-6 pb-8">

    <!-- wave -->
    <svg
      id="waveSvg"
      class="pointer-events-none absolute -top-[44px] left-0 w-full h-[88px]"
      viewBox="0 0 100 24"
      preserveAspectRatio="none"
      shape-rendering="geometricPrecision"
    >
      <path id="wavePath" fill="#8A7458"></path>
    </svg>

    <!-- nav items -->
    <ul class="absolute inset-x-0 bottom-0 flex items-end justify-center px-16 pb-4 gap-x-10 text-text">
      <li>
        <button
          class="nav-item grid h-11 w-10 place-items-center rounded-full bg-transparent translate-y-0 shadow-none transition-all duration-300 ease-out"
          data-i="0"
        >Home</button>
      </li>
      <li>
        <button
          class="nav-item grid h-11 w-10 place-items-center rounded-full bg-transparent translate-y-0 shadow-none transition-all duration-300 ease-out"
          data-i="1"
        >Search</button>
      </li>
      <li>
        <button
          class="nav-item grid h-11 w-10 place-items-center rounded-full bg-transparent translate-y-0 shadow-none transition-all duration-300 ease-out"
          data-i="2"
        >Reserve</button>
      </li>
      <li>
        <button
          class="nav-item grid h-11 w-10 place-items-center rounded-full bg-transparent translate-y-0 shadow-none transition-all duration-300 ease-out"
          data-i="3"
        >Mycafe</button>
      </li>
    </ul>
  </div>
</nav>

<script>
  const svg = document.getElementById("waveSvg");
  const path = document.getElementById("wavePath");
  const items = document.querySelectorAll(".nav-item");

  // active（丸背景）
  const activeAdd = ["bg-base", "-translate-y-4", "shadow-lg", "z-10"];
  const activeRemove = ["bg-transparent", "translate-y-0", "shadow-none", "z-0"];

  function setActive(index) {
    items.forEach((btn, i) => {
      if (i === index) {
        btn.classList.add(...activeAdd);
        btn.classList.remove(...activeRemove);
      } else {
        btn.classList.remove(...activeAdd);
        btn.classList.add(...activeRemove);
      }
    });
  }

  const BOTTOM = 24;
  const BASE_Y = 16;


  function smoothWavePath(cx, peak) {
    const humpW = 16;        
    const start = Math.max(0, cx - humpW);
    const end   = Math.min(100, cx + humpW);
    const h = humpW * 0.8;    // 0.7〜0.95（大きいほど滑らか）

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

  function calcCxFromButton(btn) {
    const svgRect = svg.getBoundingClientRect();
    const btnRect = btn.getBoundingClientRect();
    const centerX = (btnRect.left + btnRect.width / 2) - svgRect.left;
    return (centerX / svgRect.width) * 100;
  }

  let currentCx = null;
  let targetCx = null;
  let rafId = null;
  let startTime = 0;

  function moveWaveTo(cx) {
    targetCx = cx;
    if (currentCx == null) currentCx = cx;

    cancelAnimationFrame(rafId);
    startTime = performance.now();

    const duration = 420;
    const peakBase = -16; 
    const peakMin  = -20; 

    const tick = (t) => {
      const p = Math.min((t - startTime) / duration, 1);

      // cxをぬるっと追従（ここを上げると速くなる）
      currentCx += (targetCx - currentCx) * 0.22;

      // peakを一瞬バウンド
      const bounce = Math.sin(p * Math.PI); // 0→1→0
      const peak = peakBase + (peakMin - peakBase) * bounce;

      path.setAttribute("d", smoothWavePath(currentCx, peak));

      if (Math.abs(targetCx - currentCx) > 0.1 || p < 1) {
        rafId = requestAnimationFrame(tick);
      }
    };

    rafId = requestAnimationFrame(tick);
  }

  // 初期描画
  setActive(0);
  const initialCx = calcCxFromButton(items[0]);
  currentCx = initialCx;
  path.setAttribute("d", smoothWavePath(initialCx, -16));

  // クリック
  items.forEach((btn) => {
    btn.addEventListener("click", () => {
      const i = Number(btn.dataset.i);
      setActive(i);
      moveWaveTo(calcCxFromButton(btn));
    });
  });

  // リサイズ対応（ズレ防止）
  window.addEventListener("resize", () => {
    const active = document.querySelector(".nav-item.bg-base") || items[0];
    const cx = calcCxFromButton(active);
    currentCx = cx;
    targetCx = cx;
    path.setAttribute("d", smoothWavePath(cx, -16));
  });
</script>
