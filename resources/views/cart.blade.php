<x-layout>
    <x-slot:heading>
        Panier
    </x-slot:heading>

    <livewire:product.cart.sidecart />

    <x-a-button color="teal" href="{{ route('pay') }}">Payer</x-a-button>
    
</x-layout>