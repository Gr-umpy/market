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

        return $items->sortBy('variant.order')->sortBy('product.id');
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
    <p class="py-2">
        Total de la commande : {{ $this->total }}
    </p>
    <ul>
        @foreach ($this->cartItems as $cartItem)
            <li class="py-2">
                <div class="grid grid-cols-3">
                    <div>
                        <img src="{{ $cartItem->image ? Storage::url($cartItem->image->url) : '' }}" alt="{{ $cartItem->product->name }}"
                            class="w-16 h-16 object-cover">
                    </div>
                    <div>
                        Produit : {{ $cartItem->product->name }}<br>
                        variante : {{ $cartItem->variant->name }}<br>
                        Prix : {{ $cartItem->variant->price * $cartItem->quantity }}€ .
                    </div>
                    <div>
                        Quantité : {{ $cartItem->quantity }}
                    </div>
                </div>

            </li>
        @endforeach
    </ul>
</div>