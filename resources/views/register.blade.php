<x-layout>
    <x-slot:heading>
        Page d'inscription
    </x-slot:heading>
    <x-slot:button>
        <x-submit-button form="register">
            S'inscrire
        </x-submit-button>
    </x-slot:button>

    <form method="POST" action="{{ route('register.store') }}" id="register">
        @csrf
        <div class="space-y-12">
            <div class="border-b border-white/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <div class="sm:col-span-3">
                        <label for="first_name" class="block text-sm/6 font-medium text-black">Prénom</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="first_name" type="text" name="first_name" placeholder="Votre prénom" value="{{ old('first_name') }}" class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-500 focus:outline-none sm:text-sm/6" />
                            </div>
                            @error('first_name')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="last_name" class="block text-sm/6 font-medium text-black">Nom</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="last_name" type="text" name="last_name" placeholder="Votre nom" value="{{ old('last_name') }}" class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-500 focus:outline-none sm:text-sm/6" />
                            </div>
                            @error('last_name')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm/6 font-medium text-black">Email</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="email" type="email" name="email" placeholder="exemple@exemple.com" value="{{ old('email') }}" class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-500 focus:outline-none sm:text-sm/6" />
                            </div>
                            @error('email')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="password" class="block text-sm/6 font-medium text-black">Mot de passe</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="password" type="password" name="password" placeholder="Votre mot de passe" class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-500 focus:outline-none sm:text-sm/6" />
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="password_confirmation" class="block text-sm/6 font-medium text-black">Confirmer le mot de passe</label>
                        <div class="mt-2">
                            <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirmer votre mot de passe" class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-500 focus:outline-none sm:text-sm/6" />
                            </div>
                            @error('password_confirmation')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="mt-4">
        <p class="text-sm text-gray-600">Déjà un compte ? <a href="{{ route('login.show') }}" class="text-indigo-700 hover:text-indigo-500">Se connecter</a></p>
    </div>
</x-layout>
