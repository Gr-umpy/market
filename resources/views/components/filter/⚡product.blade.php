<?php

use Livewire\Component;
use Livewire\Attributes\On;

use App\Models\Product;

new class extends Component
{
    public bool $showModal = false;

    public bool $openCategoryDropdown = false;

    public string $searchTitle = '';
    public bool $withoutcategory = false;
    public bool $sellable = false;
    public string $filterType = 'all';

    /** @var array<int, array{id:int,name:string}> */
    public array $productResults = [];

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
            $this->productResults = [];
            $this->showSuggestions = false;

            return;
        }

        $pattern = implode('%', str_split($value));

        $products = Product::query()
            ->where("name", 'LIKE', '%'.$pattern.'%')
            ->orderBy('name')
            ->limit(10)
            ->get();

        $this->productResults = $products->map(fn (Product $product) => [
            'id' => $product->id,
            'name' => $product->name,
        ])->all();

        $this->showSuggestions = ! empty($this->productResults);
    }

    public function selectCategory(int $categoryId): void
    {
        $product = Product::find($categoryId);

        if (! $product) {
            return;
        }

        $this->searchTitle = $product->name;
        $this->openCategoryDropdown = false;
    }

    public function updatedFilterType(): void
    {
        $this->withoutcategory = $this->filterType == 'withoutcategory';
        $this->sellable = $this->filterType == 'sellable';
    }

    public function applyFilters()
    {
        $this->dispatch('filtersUpdated', [
            'searchTitle' => $this->searchTitle,
            'withoutcategory' => $this->withoutcategory,
            'sellable' => $this->sellable
        ]);
        $this->closeModal();
    }
};
?>

<div>
    <x-filter.livewire>
        <form wire:submit.prevent="applyFilters">
            <div class="relative flex py-1">
                <label for="search-title">Titre du produit :</label>
                <span class="px-1"></span>
                <input type="search" id="search-title" wire:model.live="searchTitle"
                    wire:click='openCategoryDropdown = true' size="40"
                    class="flex-auto rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500" />
                @if (! empty($productResults))
                    <ul @click.outside="$wire.openCategoryDropdown = false" x-show="$wire.openCategoryDropdown"
                        class="absolute z-50 top-full left-0 w-full max-h-60 overflow-auto rounded-md border border-gray-300 bg-white shadow">
                        @foreach ($productResults as $product)
                            <li wire:key="autocomplete-product-{{ $product['id'] }}"
                                wire:click="selectCategory({{ $product['id'] }})"
                                class="cursor-pointer px-3 py-1 hover:bg-gray-100">
                                {{ $product['name'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="flex py-1">
                <label for="withoutcategory">Produit sans catégories :</label>
                <span class="px-1"></span>
                <input type="radio" id="withoutcategory" wire:model='filterType' value="withoutcategory" />
            </div>
            <div class="flex py-1">
                <label for="sellable">Produits avec au moins un prix et au moins une carégorie :</label>
                <span class="px-1"></span>
                <input type="radio" id="sellable" wire:model='filterType' value="sellable" />
            </div>
            <div class="flex py-1">
                <label for="all">Tous les produits :</label>
                <span class="px-1"></span>
                <input type="radio" id="all" wire:model='filterType' value="all" />
            </div>
            <div class="py-1">
                <x-submit-button>Recherche</x-submit-button>
            </div>
        </form>
    </x-filter.livewire>
</div>