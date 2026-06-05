<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ?? 'ເຂົ້າສູ່ລະບົບ' }} — Buddhist EMS</title>

    <!-- Phetsarath + Noto: self-hosted via build | Material Symbols: jsDelivr CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-symbols@latest/outlined.css" />

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
