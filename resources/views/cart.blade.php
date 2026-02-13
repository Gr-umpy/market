<x-layout>
    <x-slot:heading>
        Panier
    </x-slot:heading>

    <livewire:product.cart.sidecart />

    <x-a-button color="teal" href="{{ route('checkout') }}">Payer</x-a-button>

    
</x-layout>