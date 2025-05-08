<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100 dark:bg-gray-900">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $title }}</title>
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg">
  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
</head>

<body class="antialiased h-full font-sans">

  <x-navbar></x-navbar>

  <x-header>{{ $title }}</x-header>

  <div class="p-4">
    {{ $slot }}
  </div>

  @stack('script')
</body>

</html>
