<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ?? 'ເຂົ້າສູ່ລະບົບ' }} — Buddhist EMS</title>

    <!-- Fonts served from build (self-hosted, no Google Fonts dependency) -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex items-center justify-center antialiased"
      style="background: linear-gradient(135deg, #545f73 0%, #374151 50%, #1f2937 100%);">

    <div class="w-full max-w-md px-4">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
