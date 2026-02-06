<?php


use Livewire\Attributes\Validate;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

new class extends Component
{
    #[Validate('required|min:3|max:255|unique:products,name')]
    public string $name = '';
    #[Validate('required|min:3|max:1023|unique:products,name')]
    public string $description = '';
    #[Validate('required|int')]
    public ?int $category_id = null;

    public ?int $user_id = null;

    public bool $showModal = false;

    public function render()
    {
        $categories = Category::query()
            ->latest()
            ->paginate(15);

        return $this->view(['categories' => $categories]);
    }

    public function save()
    {
        $this->validate();
        $this->user_id = auth()->id();

        $product = Product::create([
            'name' => $this->name,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id
        ]);

        $this->reset(['name', 'description', 'user_id', 'category_id']);
        $this->showModal = false;
        $this->dispatch('productCreated');
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
            <div class="py-2 flex items-center gap-1">
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" wire:model.live="name" required
                    class="flex-1 rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500" />
                @error('name')
                    <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                @enderror
            </div>
            <div class="py-2 flex items-start gap-3">
                <label for="description" class="w-20 pt-1 whitespace-nowrap">Description :</label>
                <textarea id="description" name="description" wire:model.live="description" required rows="2" cols="50"
                    class="flex-1 rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500" ></textarea>
                @error('description')
                    <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                @enderror
            </div>
            <div class="py-2 flex items-start gap-3">
                <label for="category">Catégorie :</label>
                <select wire:model='category_id'>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category')
                    <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                @enderror
            </div>

            <x-submit-button color="green">Crée le produit</x-submit-button>
        </form>
    </x-livewire-modal>

    @teleport('div#button-div')
    <x-button color="green" wire:click='openModal'>Crée un produit</x-button>
    @endteleport
</div>