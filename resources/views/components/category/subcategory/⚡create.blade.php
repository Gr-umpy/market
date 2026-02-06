<?php


use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\Subcategory;
use Livewire\Component;

new class extends Component
{
    #[Validate('required|min:3|max:255')]
    public string $title = '';

    public ?int $category_id = null;
    public ?string $category_name = null;

    public bool $showModal = false;

    public function save()
    {
        $this->validate();
        Subcategory::create(['name' => $this->title, 'category_id' => $this->category_id]);

        $this->reset(['title', 'category_id', 'category_name']);
        $this->showModal = false;
        $this->dispatch('subcategoryCreated');
    }

    #[On('OpenSub')]
    public function openModal($category_id, $category_name)
    {
        $this->category_id = $category_id;
        $this->category_name = $category_name;
        $this->showModal = true;
    }
};
?>

<div>
    <x-livewire-modal>
        <form wire:submit="save">
            <div class="py-2">
                <h1>Catégorie : {{ $this->category_name }}</h1>
            </div>
            <div class="py-2">
                <label for="title">Titre :</label>
                <span class="px-1"></span>
                <input type="text" id="title" name="title" wire:model.live="title" required size="50"
                    class="flex-auto rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500" />
                @error('title')
                    <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                @enderror
            </div>

            <x-submit-button color="green">Crée la sous-catégorie</x-submit-button>
        </form>
    </x-livewire-modal>
</div>