<?php

use Livewire\Component;
use App\Models\Product;

new class extends Component
{
    public function render()
    {
        $products = Product::whereHas('variants')->whereHas('categories')->get();
        $products->load(['categories', 'variants']);
        return view('components.⚡show-product', compact('products'));
    }
};
?>

<div class="grid grid-cols-3 gap-2">
    @foreach ($products as $product)
        <div class="border rounded-sm px-2">
            {{ $product->name }} <br>
            @foreach ($product->categories as $category)
                {{ $category->name }} <br>
            @endforeach
            @foreach ($product->variants as $variant)
                {{ $variant->name }} {{ $variant->formatted_price }} <br>
            @endforeach
        </div>
    @endforeach
</div>