<!DOCTYPE html>
<html class="h-full bg-gray-200">
  <head>
    <title>{{ $heading }}</title>
    <meta name="viewport" />
    <meta charset="utf-8" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
  </head>
  <body class="h-full">
    <div class="min-h-full">
      <nav class="bg-stone-700/40">
        <div class="mx-auto max-w-7xl px-2 sm:px-3 lg:px-4">
          <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
              <div class="hidden md:block">
                <div class="flex items-baseline space-x-4">
                    <x-nav-link href="{{ route('home') }}" :active="request()->is('/')">Accueil</x-nav-link>
                </div>
              </div>
            </div>
          </div>
      </nav>
      <main>
        <div class="mx-auto max-w-7xl px-2 py-6 sm:px-3 lg:px-4">
          {{ $slot }}
        </div>
      </main>
    </div>
  </body>
</html>