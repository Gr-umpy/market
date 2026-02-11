@props(['product'])

<div class="grid grid-cols-3">
    <div class="relative mt-6 flex-1 px-4 sm:px-6 max-md:hidden">
        <x-product.edit.bar-buttons :product="$product"></x-product.edit.bar-buttons>
    </div>
    <div class="md:col-span-2 max-md:col-span-3">
        {{ $slot }}
    </div>
</div>