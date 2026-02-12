<?php

use Livewire\Component;

use App\Models\Product;

new class extends Component
{
    public Product $product;

    public string $variant = '0';

    public function saveVariant(int $variantOrder) {
      $this->variant = (string)$variantOrder;
    }

    public function buy(Product $product) {
        if(session($product->id.":".$this->variant)){
            session([$product->id.":".$this->variant => session($product->id.":".$this->variant) + 1]);
        }
        else {
            session([$product->id.":".$this->variant => '1']);
        }
        $this->dispatch('cartUpdated');
    }
};
?>

<div class="p-2">
    <h1>
        Variants :
    </h1>
    <div class="grid grid-cols-[repeat(2,minmax(120px,1fr))] gap-1"> {{-- grid-cols-[repeat(auto-fit,minmax(120px,1fr))] --}}
        @foreach ($product->variants->sortBy('order') as $variant)
            <button class="p-2 {{ $this->variant == $variant->order ? 'bg-sky-600' : 'bg-sky-300 hover:bg-sky-600' }} rounded-sm" wire:click='saveVariant({{ $variant->order }})'>
                {{ $variant->name }} {{ $variant->formatted_price }}
            </button>
            
        @endforeach
    </div>

    <div class="w-full flex py-5">
        <button wire:click='buy({{ $product }})'
        class="flex-auto bg-stone-800 rounded-lg p-1 text-white hover:bg-stone-950">Ajouter au panier</button>
    </div>

</div>