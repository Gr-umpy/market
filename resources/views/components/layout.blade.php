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
              <div class="block">
                <div class="flex items-baseline space-x-4">
                    <x-nav-link href="{{ route('home') }}" :active="request()->is('/')">Accueil</x-nav-link>
                    @can('viewAny', App\Models\Category::class)  
                      <x-nav-link href="{{ route('categories') }}" :active="request()->is('catégories')">Catégories</x-nav-link>
                    @endcan
                    @can('viewAny', App\Models\Product::class)
                      <x-nav-link href="{{ route('products.index') }}" :active="request()->is('produits')">Produits</x-nav-link>
                    @endcan
                </div>
              </div>
            </div>
            <div class="flex items-center">
              <div class="block">
                <div class="flex items-center space-x-4">
                    @auth
                      <x-nav-link href="{{ route('login.show') }}" :active="request()->is('connexion')">
                        Se déconnecter
                      </x-nav-link>
                    @else  
                      <x-nav-link href="{{ route('register.show') }}" :active="request()->is('inscription') || request()->is('connexion')">
                        Se connecter
                      </x-nav-link>
                    @endauth
                    <button command="show-modal" commandfor="drawer-cart"
                    class="rounded-md px-2.5 py-1.5 text-sm font-semibold text-black inset-ring inset-ring-white/5 hover:bg-white/20">
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
                    </button>
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
        <div>
          <el-dialog>
            <dialog id="drawer-cart" aria-labelledby="drawer-title"
              class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent not-open:hidden backdrop:bg-transparent">
              <el-dialog-backdrop
                class="absolute inset-0 bg-gray-900/50 transition-opacity duration-500 ease-in-out data-closed:opacity-0"></el-dialog-backdrop>
        
              <div tabindex="0" class="absolute inset-0 pl-10 focus:outline-none sm:pl-16">
                <el-dialog-panel
                  class="group/dialog-panel relative ml-auto block size-full max-w-md transform transition duration-500 ease-in-out data-closed:translate-x-full sm:duration-700">
                  <!-- Close button, show/hide based on slide-over state. -->
                  <div
                    class="absolute top-0 left-0 -ml-8 flex pt-4 pr-2 duration-500 ease-in-out group-data-closed/dialog-panel:opacity-0 sm:-ml-10 sm:pr-4">
                    <button type="button" command="close" commandfor="drawer-cart"
                      class="relative rounded-md text-teal-400 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                      <span class="absolute -inset-2.5"></span>
                      <span class="sr-only">Fermer panier</span>
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                        aria-hidden="true" class="size-6">
                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </button>
                  </div>
        
                  <div
                    class="relative flex h-full flex-col overflow-y-auto bg-gray-600 py-6 shadow-xl after:absolute after:inset-y-0 after:left-0 after:w-px after:bg-white/10">
                    <div class="px-4 sm:px-6">
                      <h2 id="drawer-title" class="text-base font-semibold text-white">Panier</h2>
                    </div>
                    <div class="relative mt-6 flex-1 px-4 sm:px-6 text-white text-center">
                      <livewire:product.cart.sidecart />
                      <div id="bottom-cart" class="absolute bottom-0 grid grid-cols-1 justify-center">
                        <a href="{{ route('cart') }}" class="rounded-md bg-emerald-700 hover:bg-emerald-600 p-1">
                        finaliser la commande
                        </a>
                        <x-a-button color="teal" href="{{ route('checkout') }}">Payer</x-a-button>
                      </div>
                      
                    </div>
                  </div>
                </el-dialog-panel>
              </div>
            </dialog>
          </el-dialog>
        </div>
      </main>
    </div>
  </body>
</html>