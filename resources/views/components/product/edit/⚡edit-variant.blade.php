<?php

use Livewire\Component;
use App\Models\Variant;
use App\Models\Product;

new class extends Component
{
    public Product $product;
    public $variants = [];
    public array $initialVariants = [];

    public ?string $successMessage = null;

    public function rules()
    {
        return [
            'variants.*.name' => 'required',
            'variants.*.price' => 'required',
        ];
    }

    public function mount()
    {
        $this->variants = $this->product->variants()->orderBy('order')->get()->map(function ($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'price' => $variant->price,
            ];
        })->toArray();
        $this->initialVariants = $this->variants;
    }

    public function moveUp($index)
    {
        if ($index > 0) {
            $temp = $this->variants[$index - 1];
            $this->variants[$index - 1] = $this->variants[$index];
            $this->variants[$index] = $temp;
            $this->variants = array_values($this->variants);
        }
    }

    public function moveDown($index)
    {
        if ($index < count($this->variants) - 1) {
            $temp = $this->variants[$index + 1];
            $this->variants[$index + 1] = $this->variants[$index];
            $this->variants[$index] = $temp;
            $this->variants = array_values($this->variants);
        }
    }

    public function getIsDirtyProperty(): bool
    {
        return $this->variants !== $this->initialVariants;
    }

    public function addVariant()
    {
        $this->variants[] = [
            'id' => null,
            'name' => '',
            'price' => '',
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function save()
    {
        $this->authorize('update', $this->product);

        $this->validate();

        $existingIds = collect($this->variants)->pluck('id')->filter()->toArray();
        $this->product->variants()->whereNotIn('id', $existingIds)->delete();

        foreach ($this->variants as $index => $variantData) {
            $variantData['order'] = $index;
            if ($variantData['id']) {
                $variant = Variant::find($variantData['id']);
                $variant->update($variantData);
            } else {
                $variantData['product_id'] = $this->product->id;
                Variant::create($variantData);
            }
        }

        $this->successMessage = 'Variantes mises à jour avec succès !';

        $this->js('setTimeout(() => $wire.set("successMessage", null), 2000)');

        $this->mount();
    }
};
?>

<div>
    <table class="w-full">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b border-gray-600/50">Nom</th>
                <th class="py-2 px-4 border-b border-gray-600/50">Prix en €</th>
                <th class="py-2 px-4 border-b border-gray-600/50 w-20">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($variants as $index => $variant)
                <tr class="bg-green-400/10 even:bg-green-400/20">
                    <td class="py-2 px-4 border-b border-gray-600/50">
                        <input type="text" wire:model.live="variants.{{ $index }}.name" class="w-full px-2 py-1 border rounded">
                        @error('variants.'.$index.'.name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="py-2 px-4 border-b border-gray-600/50">
                        <input type="number" step="0.01" wire:model.live="variants.{{ $index }}.price" class="w-full px-2 py-1 border rounded">
                        @error('variants.'.$index.'.price')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="py-2 px-4 border-b border-gray-600/50">
                        <div class="grid grid-cols-2 items-center justify-items-center">
                            <button class="text-white bg-red-700 p-1 rounded-full cursor-pointer select-none" wire:click="removeVariant({{ $index }})" wire:confirm='Voulez vous vraiment supprimer cette variante ?'>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd"
                                        d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="flex flex-col gap-0.5">
                                <button class="text-white bg-stone-800 rounded-t-full cursor-pointer select-none {{ $index === 0 ? 'opacity-70 cursor-not-allowed' : '' }}" wire:click="moveUp({{ $index }})" @if($index === 0) disabled @endif>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M9.47 6.47a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 1 1-1.06 1.06L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button class="text-white bg-stone-800 rounded-b-full cursor-pointer select-none {{ $loop->last ? 'opacity-70 cursor-not-allowed' : '' }}" wire:click="moveDown({{ $index }})" @if($loop->last) disabled @endif>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        <button class="text-white bg-green-800 p-1 rounded-full" wire:click="addVariant">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                <path
                    d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
            </svg>
        </button>
    </div>

    <div class="mt-4">
        <x-button color="green" wire:click="save" :disabled="! $this->isDirty" class="{{ ! $this->isDirty ? 'opacity-70 cursor-not-allowed' : '' }}">Sauvegarder</x-button>
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
