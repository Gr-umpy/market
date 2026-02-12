<?php

use Livewire\Component;

use App\Models\Product;

new class extends Component
{
    public Product $product;

    public string $variant = '0';

    public ?string $successMessage = null;

    public function saveVariant(int $variantOrder)
    {
        $this->variant = (string) $variantOrder;
    }

    public function buy(Product $product)
    {
        if (session($product->id.":".$this->variant)) {
            session([$product->id.":".$this->variant => session($product->id.":".$this->variant) + 1]);
        } else {
            session([$product->id.":".$this->variant => '1']);
        }
        $this->dispatch('cartUpdated');

        $this->successMessage = 'Panier mise à jour avec succès !';

        $this->js('setTimeout(() => $wire.set("successMessage", null), 2000)');
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
    
    @if ($successMessage)
        <div class='sm:col-span-4 py-3'>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ $successMessage }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" wire:click="$set('successMessage', null)"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        </div>
    @endif
    
</div>