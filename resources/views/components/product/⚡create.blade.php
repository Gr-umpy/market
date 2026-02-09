<?php


use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Livewire\Component;

new class extends Component
{
    #[Validate('required|min:3|max:255|unique:products,name')]
    public string $name = '';
    #[Validate('required|numeric|min:0')]
    public ?float $price = null;
    #[Validate('required|min:3|max:1023')]
    public string $description = '';
    /** @var array<int, array{id:int,name:string}> */
    public array $selectedCategories = [];

    public ?int $user_id = null;

    public bool $open = false;
    public bool $showModal = false;
    public bool $showSuggestions = false;

    public string $searchCategory = '';
    /** @var array<int, array{id:int,name:string}> */
    public array $results = [];

    public function openModal()
    {
        $this->results = [];
        $this->showSuggestions = false;
        $this->showModal = true;
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->reset(['showModal', 'selectedCategories', 'searchCategory']);
        $this->results = [];
        $this->showSuggestions = false;
    }

    public function updatedSearchCategory($value)
    {
        $value = trim((string) $value);

        if ($value === '') {
            $this->results = [];
            $this->showSuggestions = false;

            return;
        }

        $pattern = implode('%', str_split($value));
        $excludedIds = array_column($this->selectedCategories, 'id');

        $categories = Category::query()
            ->where("name", 'LIKE', '%'.$pattern.'%')
            ->when(! empty($excludedIds), fn ($q) => $q->whereNotIn('id', $excludedIds))
            ->orderBy('name')
            ->limit(10)
            ->get();

        $this->results = $categories->map(fn (Category $category) => [
            'id' => $category->id,
            'name' => $category->name,
        ])->all();

        $this->showSuggestions = ! empty($this->results);
    }

    public function selectCategory(int $categoryId): void
    {
        $category = Category::find($categoryId);

        if (! $category) {
            return;
        }

        $this->addCategoryWithParents($category);

        $this->searchCategory = '';
        $this->results = [];
        $this->showSuggestions = false;
    }

    private function addCategoryWithParents(Category $category): void
    {
        foreach ($this->selectedCategories as $selected) {
            if ($selected['id'] === $category->id) {
                if ($category->category_id) {
                    $parent = Category::find($category->category_id);
                    if ($parent) {
                        $this->addCategoryWithParents($parent);
                    }
                }
                return;
            }
        }

        $this->selectedCategories[] = [
            'id' => $category->id,
            'name' => $category->name,
        ];

        if ($category->category_id) {
            $parent = Category::find($category->category_id);
            if ($parent) {
                $this->addCategoryWithParents($parent);
            }
        }
    }

    public function removeCategory(int $categoryId): void
    {
        $idsToRemove = $this->collectCategoryAndDescendants($categoryId);

        $this->selectedCategories = array_values(
            array_filter($this->selectedCategories, fn ($categorie) => ! in_array($categorie['id'], $idsToRemove))
        );
    }

    private function collectCategoryAndDescendants(int $categoryId): array
    {
        $ids = [$categoryId];
        $selectedIds = array_column($this->selectedCategories, 'id');

        $childCategories = Category::whereIn('id', $selectedIds)
            ->where('category_id', $categoryId)
            ->pluck('id');

        foreach ($childCategories as $childId) {
            $ids = array_merge($ids, $this->collectCategoryAndDescendants($childId));
        }

        return $ids;
    }

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
        ]);

        Variant::create([
            'price' => $this->price,
            'product_id' => $product->id,
        ]);

        $categoryIds = array_column($this->selectedCategories, 'id');
        $product->categories()->sync($categoryIds);

        $this->reset(['name', 'description', 'price', 'user_id', 'selectedCategories', 'searchCategory']);
        $this->showModal = false;
        $this->dispatch('productCreated');
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
            <div class="py-2 flex items-center gap-1">
                <label for="price">Prix :</label>
                <input type="number" id="price" name="price" wire:model="price" required min="0" step="0.01"
                    class="flex-1 rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500" />
                @error('price')
                    <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col py-2 gap-2">
                <div class="flex items-center">
                    <label for="filter-search-category">Catégories :</label>
                    <span class="px-1"></span>
                    <div class="relative flex-auto">
                        <input size="40" type="search" id="filter-search-category" wire:model.live="searchCategory" wire:click="$wire.open = true"
                            placeholder="Rechercher une catégorie..."
                            class="flex-auto rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500" />
                        @if (! empty($results))
                            <ul @click.outside="$wire.open = false" x-show="$wire.open"
                                class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-md border border-gray-300 bg-white shadow">
                                @foreach ($results as $categorie)
                                    <li wire:key="autocomplete-category-{{ $categorie['id'] }}" wire:click="selectCategory({{ $categorie['id'] }})"
                                        class="cursor-pointer px-3 py-1 hover:bg-gray-100">
                                        {{ $categorie['name'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                @if (! empty($selectedCategories))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($selectedCategories as $category)
                            <span wire:key="tag-category-{{ $category['id'] }}"
                                wire:click="removeCategory({{ $category['id'] }})"
                                class="inline-flex items-center gap-1 px-2 py-1 text-sm bg-indigo-100 text-indigo-700 rounded-full cursor-pointer hover:bg-indigo-200 transition-colors">
                                {{ $category['name'] }}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path
                                        d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                </svg>
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            <x-submit-button color="green">Crée le produit</x-submit-button>
        </form>
    </x-livewire-modal>

    @teleport('div#button-div')
    <x-button color="green" wire:click='openModal'>Crée un produit</x-button>
    @endteleport
</div>