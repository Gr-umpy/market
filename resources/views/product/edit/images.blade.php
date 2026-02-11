<x-layout>
    <x-slot:heading>
        Edition des photos du produit {{ $product->name }}
    </x-slot:heading>

    <x-product.edit.phone-sidebar :product="$product"></x-product.edit.phone-sidebar>

    <x-product.edit.sidebar :product="$product">
        <livewire:product.edit.image :$product />
    </x-product.edit.sidebar>

</x-layout>