<?php


use Livewire\Attributes\Validate;
use App\Models\Category;
use Livewire\Component;

new class extends Component
{
    #[Validate('required|min:3|max:255|unique:categories,name')]
    public string $title = '';

    public bool $showModal = false;

    public function save()
    {
        $this->validate();
        Category::create(['name' => $this->title]);

        $this->reset(['title']);
        $this->showModal = false;
        $this->dispatch('categoryCreated');
    }
    public function openModal()
    {
        $this->showModal = true;
    }
};
?>

<div>
    <x-livewire-modal>
        <form wire:submit="save">
            <div class="py-2">
                <label for="title">Titre :</label>
                <span class="px-1"></span>
                <input type="text" id="title" name="title" wire:model.live="title" required size="50"
                    class="flex-auto rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500" />
                @error('title')
                    <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                @enderror
            </div>
    
            <x-submit-button color="green">Crée la catégorie</x-submit-button>
        </form>
    </x-livewire-modal>

    @teleport('div#button-div')
        <x-button color="green" wire:click='openModal' >Crée une catégorie</x-button>
    @endteleport
</div>