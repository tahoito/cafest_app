<!doctype html>
<html lang="ja" class="overflow-x-hidden">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'cafest')</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            main: '#8A7458',
            main2: '#46392A',
            base_color: '#FFFAF5',
            text: '#201200',
            accent: '#E4C9A8',
            form: '#FFFFFF',
            placeholder: '#666666',
            favorite: '#4F4232',
            star: '#F6D264',
            notification: '#FF4D4D',
            notification2: '#F3F0ED',
          },
        },
      },
    }
  </script>
</head>

@php
  $welcomeBg = request()->routeIs('welcome');
  $authBg = request()->routeIs('user.login', 'user.signup', 'store.login', 'store.signup');
@endphp

<body class="min-h-screen bg-base_color text-text relative overflow-x-hidden overflow-y-hidden">
  @if($welcomeBg)
  <div class="absolute top-[15px] -left-[80px] w-[180px] h-[180px] rounded-full bg-accent"></div>
  <div class="absolute top-[30px] left-[120px] -translate-x-1/2 w-[36px] h-[36px] rounded-full bg-accent"></div>
  <div class="absolute top-[120px] -right-[50px] w-[150px] h-[150px] rounded-full bg-accent"></div>
  <div class="absolute -bottom-[120px] -left-[150px] w-[280px] h-[280px] rounded-full bg-accent"></div>
  <div class="absolute bottom-[80px] left-[150px] -translate-x-1/2 w-[44px] h-[44px] rounded-full bg-accent"></div>
  @elseif($authBg)
  <div class="fixed inset-0 overflow-hidden pointer-events-none">
    <div class="absolute -top-24 -left-24 w-56 h-56 rounded-full bg-accent"></div>
    <div class="absolute -bottom-32 -right-20 w-64 h-64 rounded-full bg-accent"></div>
    <div class="absolute bottom-10 left-40 w-10 h-10 rounded-full bg-accent"></div>
  </div>
  @endif
  <main class="relative z-10 pb-2">
    @yield('content')
  </main>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
</body>
</html>
