<?php

use Livewire\Component;
use Livewire\Attributes\On;

use App\Models\Category;

new class extends Component
{
    public bool $showModal = false;

    public bool $openCategoryDropdown = false;

    public string $searchTitle = '';

    /** @var array<int, array{id:int,name:string}> */
    public array $categoryResults = [];

    public bool $showSuggestions = false;

    public function openModal()
    {
        // On ouvre le modal en vidant les résultats d'autocomplétion
        $this->results = [];
        $this->showSuggestions = false;
        $this->showModal = true;
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->reset(['showModal']);
        $this->results = [];
        $this->showSuggestions = false;
    }

    public function updatedSearchTitle($value): void
    {
        $value = trim((string) $value);

        if ($value === '') {
            $this->categoryResults = [];
            $this->showSuggestions = false;

            return;
        }

        $pattern = implode('%', str_split($value));

        $categories = Category::query()
            ->where("name", 'LIKE', '%'.$pattern.'%')
            ->orderBy('name')
            ->limit(10)
            ->get();

        $this->categoryResults = $categories->map(fn (Category $category) => [
            'id' => $category->id,
            'name' => $category->name,
        ])->all();

        $this->showSuggestions = ! empty($this->categoryResults);
    }

    public function selectCategory(int $categoryId): void
    {
        $category = Category::find($categoryId);

        if (! $category) {
            return;
        }

        $this->searchTitle = $category->name;
        $this->openCategoryDropdown = false;
    }

    public function applyFilters()
    {
        $this->dispatch('filtersUpdated', [
            'searchTitle' => $this->searchTitle,
        ]);
        $this->closeModal();
    }
};
?>

<div>
    <x-filter.livewire>
        <form wire:submit.prevent="applyFilters">
            <div class="relative flex py-1">
                <label for="search-title">Titre de la catégorie :</label>
                <span class="px-1"></span>
                <input type="search" id="search-title" wire:model.live="searchTitle" wire:click='openCategoryDropdown = true' size="40"
                    class="flex-auto rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500" />
                @if (! empty($categoryResults))
                    <ul @click.outside="$wire.openCategoryDropdown = false" x-show="$wire.openCategoryDropdown"
                        class="absolute z-50 top-full left-0 w-full max-h-60 overflow-auto rounded-md border border-gray-300 bg-white shadow">
                        @foreach ($categoryResults as $categorie)
                            <li wire:key="autocomplete-category-{{ $categorie['id'] }}"
                                wire:click="selectCategory({{ $categorie['id'] }})" class="cursor-pointer px-3 py-1 hover:bg-gray-100">
                                {{ $categorie['name'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="py-1">
                <x-submit-button>Recherche</x-submit-button>
            </div>
        </form>
    </x-filter.livewire>
</div>