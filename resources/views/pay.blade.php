<x-layout>
    <x-slot:heading>
        Page de paie
    </x-slot:heading>

    <div class="grid grid-cols-2 gap-2">
        <livewire:pay.form />
        <livewire:pay.info />
    </div>
    
    @guest
        <div class="mt-4">
            <p class="text-sm text-gray-600">Déjà un compte ? <a href="{{ route('login.show') }}"
                    class="text-indigo-700 hover:text-indigo-500">Se connecter</a></p>
        </div>
    @endguest

    
    
</x-layout>