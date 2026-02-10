<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Product;

new class extends Component
{
    public Product $product;

    public ?string $successMessage = null;

    #[Validate('required|string|max:255')]
    public string $title;

    #[Validate('required|string')]
    public string $description;

    public function mount()
    {
        $this->title = $this->product->name;
        $this->description = $this->product->description;
    }

    public function save()
    {
        $this->authorize('update', $this->product);


        $this->validate();

        $this->product->update([
            'name' => $this->title,
            'description' => $this->description,
        ]);

        $this->successMessage = 'Informations mises à jour avec succès !';

        $this->js('setTimeout(() => $wire.set("successMessage", null), 2000)');
    }
};
?>

<div>
    <form wire:submit="save" wire:dirty.class="is-dirty">
        <div class="space-y-12">
            <div class="border-b border-white/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        
                    <div class='sm:col-span-4'>
                        <label for='title' class='block text-sm/6 font-medium text-black'>Titre</label>
                        <div class='mt-2'>
                            <div
                                class='flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500'>
                                <input required id='title' type='text' wire:model='title'
                                    class='shrink-0 text-base block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none sm:text-sm/6' />
                            </div>
                            @error('title')
                                <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class='sm:col-span-4'>
                        <label for='description' class='block text-sm/6 font-medium text-black'>Description</label>
                        <div class='mt-2'>
                            <div
                                class='flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500'>
                                <textarea required id='description' type='text' wire:model='description' rows="2"
                                    class='shrink-0 text-base block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none sm:text-sm/6'></textarea>
                            </div>
                            @error('description')
                                <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
        
                </div>
            </div>
        </div>
    
        <x-submit-button color="green" wire:dirty.remove.attr="disabled" disabled wire:dirty.class.remove="opacity-70 cursor-not-allowed" class="opacity-70 cursor-not-allowed">Éditer le produit</x-submit-button>
    </form>

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