<x-layout>
    <x-slot:heading>
        Edition des variantes du produit {{ $product->name }}
    </x-slot:heading>

    <x-product.edit.phone-sidebar :product="$product"></x-product.edit.phone-sidebar>
    
    <x-product.edit.sidebar :product="$product">
        <livewire:product.edit.variant  :$product />
    </x-product.edit.sidebar>

</x-layout>