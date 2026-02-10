<?php

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

new class extends Component
{
    public Product $product;

    public bool $isDirty = false;

    /** @var array<int, array{id:int,name:string}> */
    public array $selectedCategories = [];

    public string $searchCategory = '';

    /** @var array<int, array{id:int,name:string}> */
    public array $categoryResults = [];

    public bool $openCategoryDropdown = false;

    public ?string $successMessage = null;

    public array $initialCategoryIds = [];

    public function mount(): void
    {
        $this->selectedCategories = $this->product->categories->map(fn ($cat) => [
            'id' => $cat->id,
            'name' => $cat->name,
        ])->all();
        $this->initialCategoryIds = array_column($this->selectedCategories, 'id');
        sort($this->initialCategoryIds);
        $this->searchCategory = '';
        $this->categoryResults = [];
        $this->isDirty = false;
        $this->dispatch('editCategoryDirty', false);
    }

    public function getIsDirtyProperty(): bool
    {
        $currentIds = array_column($this->selectedCategories, 'id');
        sort($currentIds);
        return $currentIds !== $this->initialCategoryIds;
    }

    public function updatedSearchCategory($value): void
    {
        $value = trim((string) $value);

        if ($value === '') {
            $this->categoryResults = [];
            return;
        }

        $pattern = implode('%', str_split($value));
        $excludedIds = array_column($this->selectedCategories, 'id');

        $categories = Category::query()
            ->where('name', 'LIKE', '%'.$pattern.'%')
            ->when(! empty($excludedIds), fn ($q) => $q->whereNotIn('id', $excludedIds))
            ->orderBy('name')
            ->limit(10)
            ->get();

        $this->categoryResults = $categories->map(fn (Category $cat) => [
            'id' => $cat->id,
            'name' => $cat->name,
        ])->all();
    }

    public function selectCategoryForEdit(int $categoryId): void
    {
        $category = Category::find($categoryId);

        if (! $category) {
            return;
        }

        $this->addCategoryWithParents($category);

        $this->searchCategory = '';
        $this->categoryResults = [];
        $this->openCategoryDropdown = false;
        $this->isDirty = $this->getIsDirtyProperty();
        $this->dispatch('editCategoryDirty', $this->isDirty);
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

    public function removeCategoryFromEdit(int $categoryId): void
    {
        $idsToRemove = $this->collectCategoryAndDescendants($categoryId);

        $this->selectedCategories = array_values(
            array_filter($this->selectedCategories, fn ($cat) => ! in_array($cat['id'], $idsToRemove))
        );
        $this->isDirty = $this->getIsDirtyProperty();
        $this->dispatch('editCategoryDirty', $this->isDirty);
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

    public function saveCategories(): void
    {
        $this->authorize('update', $this->product);

        $categoryIds = array_column($this->selectedCategories, 'id');
        $this->product->categories()->sync($categoryIds);

        $this->initialCategoryIds = $categoryIds;
        sort($this->initialCategoryIds);

        $this->isDirty = false;
        $this->dispatch('editCategoryDirty', false);

        $this->successMessage = 'Catégories mises à jour avec succès !';

        $this->js('setTimeout(() => $wire.set("successMessage", null), 2000)');
    }
};
?>

<div>
    <div class="space-y-12">
        <div class="border-b border-white/10 pb-12">
            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
    
                <div class='sm:col-span-4'>
                    <label for='search-category' class='block text-sm/6 font-medium text-black'>Catégories de "{{ $this->product->name }}" :</label>
                    <div class='mt-2'>
                        <div class='relative flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500'>
                            <input required id='search-category' type='search' wire:model.live='searchCategory' wire:click="openCategoryDropdown = true" placeholder="Rechercher une catégorie..."
                                class='shrink-0 text-base block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none sm:text-sm/6' />
                            @if (! empty($categoryResults))
                                <ul @click.outside="$wire.openCategoryDropdown = false" x-show="$wire.openCategoryDropdown"
                                    class="absolute z-50 top-full left-0 w-full max-h-60 overflow-auto rounded-md border border-gray-300 bg-white shadow">
                                    @foreach ($categoryResults as $categorie)
                                        <li wire:key="edit-autocomplete-category-{{ $categorie['id'] }}"
                                            wire:click="selectCategoryForEdit({{ $categorie['id'] }})" class="cursor-pointer px-3 py-1 hover:bg-gray-100">
                                            {{ $categorie['name'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        @error('search-category')
                            <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class='sm:col-span-4'>
                    <div class="flex flex-wrap gap-2 min-h-10 py-2">
                        @forelse ($selectedCategories as $category)
                            <span wire:key="edit-tag-category-{{ $category['id'] }}" wire:click="removeCategoryFromEdit({{ $category['id'] }})"
                                class="inline-flex items-center gap-1 px-2 py-1 text-sm bg-indigo-100 text-indigo-700 rounded-full cursor-pointer hover:bg-indigo-200 transition-colors">
                                {{ $category['name'] }}
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path
                                        d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                </svg>
                            </span>
                        @empty
                            <span class="text-gray-400 text-sm">Aucune catégorie sélectionnée</span>
                        @endforelse
                    </div>
                </div>
                <div class='sm:col-span-4'>
                    <x-button wire:click="saveCategories" color="green" :disabled="! $this->isDirty" class="{{ ! $this->isDirty ? 'opacity-70 cursor-not-allowed' : '' }}">Enregistrer les catégories</x-button>
                </div>

                @if ($successMessage)
                    <div class='sm:col-span-4'>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ $successMessage }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button" wire:click="$set('successMessage', null)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>


</div>