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
                    <x-nav-link href="{{ route('category') }}" :active="request()->is('catégories')">Catégories</x-nav-link>
                </div>
              </div>
            </div>
            <div class="flex items-center">
              <div class="hidden md:block">
                <div class="flex items-baseline space-x-4">
                  <x-nav-link href="{{ route('login.show') }}" :active="request()->is('connexion')">
                    @auth
                      Se déconnecter
                    @else  
                      Se connecter
                    @endauth
                  </x-nav-link>
                  <a href="{{ route('cart') }}" class="self-center">
                    @if (request()->is('panier'))
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path
                          d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                      </svg>
                    @else
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                      </svg>
                    @endif
                  </a>
                </div>
              </div>
            </div>
          </div>
      </nav>
      <header
        class="relative bg-stone-700/70 after:pointer-events-none after:absolute after:inset-x-0 after:inset-y-0 after:border-y after:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex justify-between items-center">
          <h1 class="text-3xl font-bold tracking-tight text-white">{{ $heading }}</h1>
          <div id="button-div" class="flex gap-1 items-center">{{ isset($button) ? $button : '' }}</div>
          
        </div>
      </header>
      <main>
        <div class="mx-auto max-w-7xl px-2 py-6 sm:px-3 lg:px-4">
          {{ $slot }}
        </div>
      </main>
    </div>
  </body>
</html>