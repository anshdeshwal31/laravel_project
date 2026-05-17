<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" id="app-body" data-theme="dark">
        <div class="app-shell">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="relative z-10 border-b border-white/10 bg-slate-950/55 backdrop-blur-2xl light:border-slate-200 light:bg-white/80">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative z-10">
                {{ $slot }}
            </main>
        </div>
    </body>
    <script>
        (function() {
            function applyTheme(theme) {
                const htmlElement = document.documentElement;
                const body = document.getElementById('app-body');
                const themeButton = document.getElementById('theme-toggle');

                if (body) {
                    body.setAttribute('data-theme', theme);
                }

                if (htmlElement) {
                    htmlElement.setAttribute('data-theme', theme);
                    htmlElement.classList.toggle('dark', theme === 'dark');
                    htmlElement.classList.toggle('light', theme === 'light');
                }

                if (themeButton) {
                    themeButton.textContent = theme === 'dark' ? '☀️' : '🌙';
                }

                localStorage.setItem('theme', theme);
            }

            function initTheme() {
                const savedTheme = localStorage.getItem('theme') || 'dark';
                applyTheme(savedTheme);

                const themeButton = document.getElementById('theme-toggle');
                if (themeButton && !themeButton.dataset.bound) {
                    themeButton.dataset.bound = 'true';
                    themeButton.addEventListener('click', function() {
                        const currentTheme = localStorage.getItem('theme') || 'dark';
                        applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTheme, { once: true });
            } else {
                initTheme();
            }
        })();
    </script>
</html>
