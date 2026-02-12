<x-layout>
    <x-slot:heading>
        {{ $product->name }}
    </x-slot:heading>

    <div class="grid grid-cols-4">
        
        <div class="col-span-3 grid grid-cols-2 auto-rows-auto">
            @foreach ($product->images as $image)
                <div>
                    <img src="{{ Storage::url($image->url) }}" />
                </div>
            @endforeach
            
        </div>

        <livewire:product.cart.buying :$product />

    </div>

</x-layout>