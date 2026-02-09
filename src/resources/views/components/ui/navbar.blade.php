@php
  $isHome = request()->routeIs('user.top') || request()->routeIs('user.recommended');
  $isSearch = request()->routeIs('user.search');
  $isReserve = request()->routeIs('user.reserve') || request()->routeIs('user.reserve.*');
  $isMycafe = request()->routeIs('user.mycafe') || request()->routeIs('user.mycafe.*');
@endphp

<div
  x-data
  :class="($store.favModal?.openStoreId !== null || $store.favModal?.createStoreId !== null)
    ? 'pointer-events-none'
    : 'pointer-events-auto'"
  class="fixed inset-x-0 bottom-0 z-[100]"
>
  <nav role="navigation" class="menu relative flex w-full items-center justify-around bg-main px-4 py-3">
    <a href="{{ route('user.top') }}"
       class="menu__item {{ $isHome ? 'active' : '' }}">
      <span class="menu__icon">
        <x-icons.home size="34" class="nav-draw"/>
      </span>
    </a>

    <a href="{{ route('user.search') }}"
       class="menu__item {{ $isSearch ? 'active' : '' }}">
      <span class="menu__icon">
        <x-icons.search size="34" class="nav-draw"/>
      </span>
    </a>

    <a href="{{ route('user.reserve') }}"
       class="menu__item {{ $isReserve ? 'active' : '' }}">
      <span class="menu__icon">
        <x-icons.reserve size="34" class="nav-draw"/>
      </span>
    </a>

    <a href="{{ route('user.mycafe') }}"
       class="menu__item {{ $isMycafe ? 'active' : '' }}">
      <span class="menu__icon">
        <x-icons.mycafe size="34" class="nav-draw"/>
      </span>
    </a>

    <div class="menu__border"></div>
  </nav>

  {{-- clip-path --}}
  <svg class="h-0 w-0" viewBox="0 0 202.9 45.5">
    <clipPath id="menu" clipPathUnits="objectBoundingBox"
              transform="scale(0.0049285362247413 0.021978021978022)">
      <path d="M6.7,45.5c5.7,0.1,14.1-0.4,23.3-4c5.7-2.3,9.9-5,18.1-10.5c10.7-7.1,11.8-9.2,20.6-14.3c5-2.9,9.2-5.2,15.2-7
          c7.1-2.1,13.3-2.3,17.6-2.1c4.2-0.2,10.5,0.1,17.6,2.1c6.1,1.8,10.2,4.1,15.2,7c8.8,5,9.9,7.1,20.6,14.3c8.3,5.5,12.4,8.2,18.1,10.5
          c9.2,3.6,17.6,4.2,23.3,4H6.7z"/>
    </clipPath>
  </svg>
</div>

<script>
"use strict";

(() => {
  const menu = document.querySelector(".menu");
  if (!menu) return;

  const menuItems = menu.querySelectorAll(".menu__item");
  const menuBorder = menu.querySelector(".menu__border");
  let activeItem = menu.querySelector(".active");

  // ========= 設定 =========
  // 遷移をわざと遅らせるならここを 80〜120 程度に（基本は 0 推奨）
  const NAV_DELAY_MS = 0;
  // prefetch を使うか（体感速度UP）
  const ENABLE_PREFETCH = true;

  if (!activeItem && menuBorder) {
    menuBorder.style.opacity = "0";
  }

  function offsetMenuBorder(element) {
    if (!menuBorder || !element) return;
    const rect = element.getBoundingClientRect();
    const left = Math.floor(
      rect.left - menu.offsetLeft - (menuBorder.offsetWidth - rect.width) / 2
    ) + "px";
    menuBorder.style.transform = `translate3d(${left}, 0, 0)`;
  }

  function setActive(item) {
    if (!item) return;
    if (activeItem) activeItem.classList.remove("active");
    activeItem = item;
    activeItem.classList.add("active");
    if (menuBorder) menuBorder.style.opacity = "1";
    offsetMenuBorder(activeItem);
  }

  // ✅ “元のアイコンは消さずに” 上から線だけなぞる
  function drawIconOverlay(item) {
    const svg = item.querySelector("svg.nav-draw");
    if (!svg) return;

    // 前のoverlayがあれば消す
    svg.querySelectorAll(".draw-overlay").forEach(n => n.remove());

    const shapes = svg.querySelectorAll("path,circle,rect,line,polyline,polygon");
    if (!shapes.length) return;

    const overlay = document.createElementNS("http://www.w3.org/2000/svg", "g");
    overlay.setAttribute("class", "draw-overlay");
    overlay.style.pointerEvents = "none";

    shapes.forEach((el) => {
      // strokeがない（=線じゃない）要素はスキップ
      const stroke = el.getAttribute("stroke");
      const hasStroke = stroke && stroke !== "none";
      if (!hasStroke) return;

      const clone = el.cloneNode(true);

      // 線だけ描きたいのでfill消す
      clone.setAttribute("fill", "none");

      if (typeof clone.getTotalLength === "function") {
        const len = clone.getTotalLength();
        clone.style.strokeDasharray = `${len}`;
        clone.style.strokeDashoffset = `${len}`;
        clone.style.transition = "none";
        overlay.appendChild(clone);
      }
    });

    if (!overlay.childNodes.length) return;

    svg.appendChild(overlay);

    // 次フレームで描き始め
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        overlay.querySelectorAll("path,circle,rect,line,polyline,polygon").forEach((el) => {
          if (typeof el.getTotalLength !== "function") return;
          el.style.transition = "stroke-dashoffset 520ms ease";
          el.style.strokeDashoffset = "0";
        });
      });
    });

    // 終わったらoverlay消す（元のアイコンは残る）
    setTimeout(() => overlay.remove(), 650);
  }

  // ========= Prefetch（同一オリジンの document を先読み） =========
  function prefetchDocument(href) {
    if (!ENABLE_PREFETCH) return;
    if (!href) return;

    let url;
    try {
      url = new URL(href, window.location.origin);
    } catch {
      return;
    }
    if (url.origin !== window.location.origin) return;

    const pathname = url.pathname + url.search;
    // 同じページや、すでにprefetch済みはスキップ
    if (pathname === (window.location.pathname + window.location.search)) return;
    if (document.querySelector(`link[rel="prefetch"][href="${pathname}"]`)) return;

    const link = document.createElement("link");
    link.rel = "prefetch";
    link.as = "document";
    link.href = pathname;
    document.head.appendChild(link);
  }

  // 初期位置（初回だけはアニメなし）
  if (activeItem) {
    menu.style.setProperty("--timeOut", "0s");
    offsetMenuBorder(activeItem);
    requestAnimationFrame(() => {
      menu.style.removeProperty("--timeOut");
    });
  }

  menuItems.forEach((item) => {
    const href = item.getAttribute("href") || "";

    // hover / touchstart で先読み（スマホは touchstart が効く）
    item.addEventListener("mouseenter", () => prefetchDocument(href));
    item.addEventListener("touchstart", () => prefetchDocument(href), { passive: true });

    // 押した瞬間に “押した感” を出す（遷移待ちはしない）
    item.addEventListener("pointerdown", (e) => {
      // 右クリック等は除外
      if (e.button !== 0) return;
      if (activeItem !== item) setActive(item);
      drawIconOverlay(item);
    });

    // click では「同一ページなら遷移止める」だけやる
    item.addEventListener("click", (e) => {
      const isModified =
        e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button === 1;

      const isRealNav = href && href !== "#" && !href.startsWith("#");
      if (!isRealNav || isModified) return;

      // 同じURLなら何もしない（無駄なリロード防止）
      let url;
      try {
        url = new URL(href, window.location.origin);
      } catch {
        return;
      }
      const same =
        url.pathname === window.location.pathname &&
        url.search === window.location.search;

      if (same) {
        e.preventDefault();
        return;
      }

      // 遷移を遅らせたい時だけここで止める（基本0でOK）
      if (NAV_DELAY_MS > 0) {
        e.preventDefault();
        setTimeout(() => {
          window.location.href = href;
        }, NAV_DELAY_MS);
      }
      // NAV_DELAY_MS === 0 の場合は、preventDefaultしない＝即遷移
    });
  });

  window.addEventListener("resize", () => {
    offsetMenuBorder(activeItem);
    menu.style.setProperty("--timeOut", "none");
  });
})();
</script>
