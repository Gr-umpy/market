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
                    <x-nav-link href="{{ route('home') }}" :active="request()->is('/')">Catégories</x-nav-link>
                </div>
              </div>
            </div>
            <div class="flex items-center">
              <div class="hidden md:block">
                <div class="flex items-baseline space-x-4">
                  <a href="{{ route('home') }}" class="self-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                  </a>
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