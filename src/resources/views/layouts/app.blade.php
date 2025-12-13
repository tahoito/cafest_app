<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'cafest')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <scripts src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        main: '#8A7458',
                        main2: '#46392A',
                        base: '#FFFAF5',
                        text: '#201200',
                        accent: '#E4C9A8',
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

    <body class="min-h-screen bg-base text-text">
        <main class="mx-auto w-full max-w-7xl px-4 py-6">
            @yield('content')
        </main>
    </body>
</html>