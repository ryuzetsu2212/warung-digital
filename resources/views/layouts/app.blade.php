<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Warung Digital' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- html2canvas for QR PNG download -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        @livewireStyles
    </head>
    <body class="bg-slate-900 text-slate-100 font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-amber-500 selection:text-slate-950">
        <div class="flex-1">
            {{ $slot }}
        </div>

        <footer class="text-center py-6 text-xs text-slate-500 border-t border-slate-800">
            &copy; {{ date('Y') }} Sistem Pemesanan Warung Berbasis QR Code. All rights reserved.
        </footer>

        @livewireScripts
    </body>
</html>
