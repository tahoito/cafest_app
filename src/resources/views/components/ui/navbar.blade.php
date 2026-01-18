<div
  x-data
  :class="($store.favModal?.openStoreId !== null || $store.favModal?.createStoreId !== null)
    ? 'pointer-events-none'
    : 'pointer-events-auto'"
  class="fixed inset-x-0 bottom-0 z-[100]"
>
  <nav role="navigation" class="menu relative flex w-full items-center justify-around bg-main px-4 py-3">
    <a href="{{ route('user.top') }}"
       class="menu__item {{ request()->routeIs('user.top') ? 'active' : '' }}">
      <span class="menu__icon">
        <x-icons.home size="34" class="nav-draw"/>
      </span>
    </a>

    <a href="{{ route('user.search') }}"
       class="menu__item {{ request()->routeIs('user.search') ? 'active' : '' }}">
      <span class="menu__icon">
        <x-icons.search size="34" class="nav-draw"/>
      </span>
    </a>

    <a href="{{ route('user.reserve') }}"
       class="menu__item {{ request()->routeIs('user.reserve') ? 'active' : '' }}">
      <span class="menu__icon">
        <x-icons.reserve size="34" class="nav-draw"/>
      </span>
    </a>

    <a href="{{ route('user.mycafe') }}"
       class="menu__item">
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

const menu = document.querySelector(".menu");
if (menu) {
  const menuItems = menu.querySelectorAll(".menu__item");
  const menuBorder = menu.querySelector(".menu__border");
  let activeItem = menu.querySelector(".active") || menuItems[0];

  function offsetMenuBorder(element) {
    if (!menuBorder || !element) return;
    const rect = element.getBoundingClientRect();
    const left = Math.floor(
      rect.left - menu.offsetLeft - (menuBorder.offsetWidth - rect.width) / 2
    ) + "px";
    menuBorder.style.transform = `translate3d(${left}, 0 , 0)`;
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

      // 元のstroke色/太さを保持（ここ大事）
      // clone.setAttribute("stroke", "currentColor"); ←やらない

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

  // 初期位置（初期は描かない＝押した時だけ）
  offsetMenuBorder(activeItem);

  menuItems.forEach((item) => {
    item.addEventListener("click", (e) => {
      const href = item.getAttribute("href") || "";
      const isRealNav = href && href !== "#" && !href.startsWith("#");

      if (activeItem) activeItem.classList.remove("active");
      item.classList.add("active");
      activeItem = item;

      offsetMenuBorder(activeItem);
      drawIconOverlay(activeItem);

      // 遷移はちょい遅らせて“描き始め”が見えるように
      if (isRealNav) {
        e.preventDefault();
        setTimeout(() => { window.location.href = href; }, 220);
      }
    });
  });

  window.addEventListener("resize", () => {
    offsetMenuBorder(activeItem);
    menu.style.setProperty("--timeOut", "none");
  });
}
</script>
