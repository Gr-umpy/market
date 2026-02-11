<x-layout>
    <x-slot:heading>
        @auth
            Page de déconnexion
        @else
            Page de connexion
        @endauth
    </x-slot:heading>
    <x-slot:button>
        @auth
            <x-submit-button form="logout">
                Déconnexion
            </x-submit-button>

        @else
            <x-submit-button form="login">
                Connexion
            </x-submit-button>
        @endauth
    </x-slot:button>
    @auth
        <div>
            <h2>Vous êtes actuellement connecté en temps que {{ auth()->user()['first_name'] }}
                {{ auth()->user()['last_name'] }}</h2>
        </div>
        <form method="POST" action="{{ route('login.logout') }}" id="logout">
            @csrf
        </form>
    @else
        <form method="POST" action="{{ route('login.login') }}" id="login">
            @csrf
            <div class="space-y-12">
                <div class="border-b border-white/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm/6 font-medium text-black">Email</label>
                            <div class="mt-2">
                                <div
                                    class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                    <input required id="email" type="text" name="email" placeholder="exemple@exemple.com"
                                        value="{{ old('email') }}"
                                        class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                                </div>
                                @error('email')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="password" class="block text-sm/6 font-medium text-black">Mot de passe</label>
                            <div class="mt-2">
                                <div
                                    class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                    <input required id="password" type="password" name="password" placeholder="password"
                                        class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                                </div>
                                @error('password')
                                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="mt-4">
            <p class="text-sm text-gray-600">Pas de compte ? <a href="{{ route('register.show') }}" class="text-indigo-700 hover:text-indigo-500">S'inscrire</a></p>
        </div>
    @endauth



</x-layout>