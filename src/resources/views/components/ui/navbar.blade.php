<div class="fixed inset-x-0 bottom-0 z-[100]">
  <nav role="navigation"
       class="menu relative flex w-full items-center justify-around bg-main px-4 py-3">

    <a href="{{ route('user.top') }}"
       class="menu__item {{ request()->routeIs('user.top') ? 'active' : '' }}">
      <x-icons.home size="34"/>
    </a>

    <a href="{{ route('user.search') }}"
       class="menu__item {{ request()->routeIs('user.search') ? 'active' : '' }}">
      <x-icons.search size="34"/>
    </a>

    <a href="#" class="menu__item">
      <x-icons.reserve size="34"/>
    </a>

    <a href="#" class="menu__item">
      <x-icons.mycafe size="34"/>
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

  // 初期位置
  offsetMenuBorder(activeItem);

  menuItems.forEach((item) => {
    item.addEventListener("click", (e) => {
      // hrefがない/ # のときだけJSでactive切替（遷移系はBladeに任せる）
      const href = item.getAttribute("href") || "";
      const isRealNav = href && href !== "#" && !href.startsWith("#");

      // 波だけは遷移前に動かす（体感UP）
      if (activeItem) activeItem.classList.remove("active");
      item.classList.add("active");
      activeItem = item;
      offsetMenuBorder(activeItem);

      // 本物のページ遷移は少し遅らせる
      if (isRealNav) {
        e.preventDefault();
        const url = href;
        setTimeout(() => { window.location.href = url; }, 150);
      }
    });
  });

  window.addEventListener("resize", () => {
    offsetMenuBorder(activeItem);
    menu.style.setProperty("--timeOut", "none");
  });
}
</script>
