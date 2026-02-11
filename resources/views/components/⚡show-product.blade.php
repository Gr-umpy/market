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
         <div class="hover:border hover:border-amber-600 rounded-sm p-1">
            @foreach ($product->images as $image)
                @if ($image->order == 0)
                    <img src="{{ Storage::url($image->url) }}" class="h-70 w-full"/>
                @endif
            @endforeach
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