<?php

use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    protected $listeners = [
        'cartUpdated' => '$refresh'
    ];

    #[Computed]
    public function cartItems()
    {
        $items = collect();

        foreach (session()->except(['_token', '_flash', '_previous']) as $key => $value) {
            $items->push((object) [
                'id' => explode(':', $key)[0],
                'order' => explode(':', $key)[1],
                'quantity' => $value,
            ]);
        }

        return $items;
    }
};
?>

<div>
    <ul>
        @foreach ($this->cartItems as $cartItem)
            <li class="py-2">
                clé du produit {{ $cartItem->id }},
                ordre de la variante {{ $cartItem->order }},
                quantité {{ $cartItem->quantity }}.
            </li>
        @endforeach
    </ul>
</div>
