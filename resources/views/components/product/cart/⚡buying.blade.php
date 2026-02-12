<?php

use Livewire\Component;

use App\Models\Product;

new class extends Component
{
    public Product $product;

    public string $variant = '';

    public function saveVariant(int $variantId) {
      $this->variant = (string)$variantId;
    }

    public function buy(Product $product) {
      session([$product->id." : ".$this->variant => '1']);
    }
};
?>

<div class="p-2">
    <h1>
        Variants :
    </h1>
    <div class="grid grid-cols-[repeat(2,minmax(120px,1fr))] gap-1"> {{-- grid-cols-[repeat(auto-fit,minmax(120px,1fr))] --}}
        @foreach ($product->variants as $variant)
            
            <button class="p-2 bg-sky-300 hover:bg-sky-600 rounded-sm" wire:click='saveVariant({{ $variant->id }})'>
                {{ $variant->name }} {{ $variant->formatted_price }}
            </button>
            
        @endforeach
    </div>

    <div class="w-full flex py-5">
        <button wire:click='buy({{ $product }})'
        class="flex-auto bg-stone-800 rounded-lg p-1 text-white hover:bg-stone-950">Ajouter au panier</button>
    </div>

</div>