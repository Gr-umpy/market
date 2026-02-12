<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Product;

new class extends Component
{
    protected $listeners = [
        'cartUpdated' => '$refresh'
    ];

    #[Computed]
    public function cartItems()
    {
        $items = collect();

        $sessionData = collect(session()->all())
            ->except(['_token', '_flash', '_previous'])
            ->filter(fn ($value, $key) => ! str_starts_with($key, 'login_web'));

        $productIds = [];
        $cartData = [];

        foreach ($sessionData as $key => $value) {
            $parts = explode(':', $key);
            $productId = $parts[0];
            $order = $parts[1];
            $productIds[] = $productId;
            $cartData[] = [
                'id' => $productId,
                'order' => $order,
                'quantity' => $value,
            ];
        }

        $products = Product::with('variants', 'images')->whereIn('id', array_unique($productIds))->get()->keyBy('id');

        foreach ($cartData as $data) {
            $product = $products[$data['id']];
            $variant = $product->variants->where('order', $data['order'])->first();
            $image = $product->images->where('order', 0)->first();
            $items->push((object) [
                'product' => $product,
                'variant' => $variant,
                'image' => $image,
                'quantity' => $data['quantity'],
            ]);
        }

        return $items;
    }

    public function more($product_id, $variant_order)
    {
        session([$product_id.":".$variant_order => session($product_id.":".$variant_order) + 1]);
        $this->dispatch('cartUpdated');
    }

    public function less($product_id, $variant_order)
    {
        if (session($product_id.":".$variant_order) > 1) {
            session([$product_id.":".$variant_order => session($product_id.":".$variant_order) - 1]);
            }
        elseif (session($product_id.":".$variant_order) == 1) {
            session()->forget($product_id.":".$variant_order);
        }
        $this->dispatch('cartUpdated');
    }

    #[Computed]
    public function total()
    {
        $total = 0;
        foreach ($this->cartItems as $cartItem) {
            $total += $cartItem->variant->price * $cartItem->quantity;
        }
        return $total;
    }
};
?>

<div>
    <ul>
        @foreach ($this->cartItems as $cartItem)
            <li class="py-2">
                <div class="grid grid-cols-3">
                    <div>
                        <img src="{{ $cartItem->image->url ?? '' }}" alt="{{ $cartItem->product->name }}" class="w-16 h-16 object-cover">
                    </div>
                    <div>
                        Produit : {{ $cartItem->product->name }}<br>
                        variante : {{ $cartItem->variant->name }}<br>
                        Prix : {{ $cartItem->variant->price * $cartItem->quantity }}€ .
                    </div>
                    <div>
                        <button
                            class="text-white bg-stone-800 rounded-t-full cursor-pointer select-none"
                            wire:click="more({{ $cartItem->product->id }}, {{ $cartItem->variant->order }})">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd"
                                    d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button> <br>
                        Quantité : {{ $cartItem->quantity }} <br>
                        <button
                            class="text-white bg-stone-800 rounded-b-full cursor-pointer select-none"
                            wire:click="less({{ $cartItem->product->id }}, {{ $cartItem->variant->order }})">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd"
                                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

            </li>
        @endforeach
    </ul>
    <p class="py-2">
        Total de la commande : {{ $this->total }}
    </p>
</div>
