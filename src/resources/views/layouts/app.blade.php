<!doctype html>
<html lang="ja" class="overflow-x-hidden">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#8A735A">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Cafest">

  <title>@yield('title', 'cafest')</title>
  <link rel="manifest" href="/manifest.webmanifest" crossorigin="use-credentials">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  <link rel="icon" href="/favicon.ico">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  @vite(['src/resources/css/app.css', 'src/resources/js/app.js'])
</head>

@php
  $welcomeBg = request()->routeIs('welcome');
  $authBg = request()->routeIs('user.login', 'user.signup', 'store.login', 'store.signup');
@endphp

<body class="min-h-screen bg-base_color text-text_color relative overflow-x-hidden">

  {{-- ▼ 背景丸レイヤー（zだけ変更） --}}
  <div class="fixed inset-0 overflow-hidden pointer-events-none z-[-1]">
    @if($welcomeBg)
      <div class="absolute top-[15px] -left-[80px] w-[180px] h-[180px] rounded-full bg-accent"></div>
      <div class="absolute top-[30px] left-[120px] -translate-x-1/2 w-[36px] h-[36px] rounded-full bg-accent"></div>
      <div class="absolute top-[120px] -right-[50px] w-[150px] h-[150px] rounded-full bg-accent"></div>
      <div class="absolute -bottom-[120px] -left-[150px] w-[280px] h-[280px] rounded-full bg-accent"></div>
      <div class="absolute bottom-[80px] left-[150px] -translate-x-1/2 w-[44px] h-[44px] rounded-full bg-accent"></div>
    @elseif($authBg)
      <div class="absolute -top-24 -left-24 w-56 h-56 rounded-full bg-accent"></div>
      <div class="absolute -bottom-32 -right-20 w-64 h-64 rounded-full bg-accent"></div>
      <div class="absolute bottom-10 left-40 w-10 h-10 rounded-full bg-accent"></div>
    @endif
  </div>

  {{-- ▼ メインコンテンツ --}}
  <main
    class="relative z-10 @unless(View::hasSection('hideNavbar')) pb-20 @else pb-2 @endunless"
  >
    @yield('content')
  </main>

  @unless(View::hasSection('hideNavbar'))
    <x-ui.navbar />
  @endunless

  <x-ui.modal.review-detail />
  @stack('scripts')
</body>
</html>
