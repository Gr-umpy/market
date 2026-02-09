<?php

use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

new class extends Component
{
    use WithPagination;

    public ?int $editingId = null;
    #[Validate('required|min:3|max:255')]
    public string $editingName = '';

    public bool $showTable = false;

    public bool $showCategoryModal = false;
    public ?int $editingCategoryProductId = null;
    public string $editingCategoryProductName = '';
    /** @var array<int, array{id:int,name:string}> */
    public array $selectedCategories = [];
    public string $searchCategory = '';
    /** @var array<int, array{id:int,name:string}> */
    public array $categoryResults = [];
    public bool $openCategoryDropdown = false;

    protected $listeners = [
        'productCreated' => '$refresh',
        'productDeleted' => '$refresh',
        'productUpdated' => '$refresh'
    ];

    public function render()
    {
        $products = Product::with(['user', 'categories'])
            ->latest()
            ->paginate(15);

        $this->showTable = $products->isNotEmpty();

        return $this->view(['products' => $products]);
    }

    public function edit(Product $product)
    {
        $this->editingId = $product->id;
        $this->editingName = $product->name;
    }

    public function update()
    {
        $this->validate();

        $this->validate([
            'editingName' => 'required|min:3|max:255|unique:products,name,'.$this->editingId,
        ]);

        $product = Product::find($this->editingId);

        $product->update(['name' => $this->editingName]);

        $this->dispatch('productUpdated');
        $this->cancelEdit();
    }

    public function cancelEdit()
    {
        $this->editingId = null;
        $this->editingName = '';
    }

    public function delete(Product $product)
    {
        $product->delete();
        $this->dispatch('productDeleted');
    }

    public function openCategoryModal(Product $product): void
    {
        $this->editingCategoryProductId = $product->id;
        $this->editingCategoryProductName = $product->name;
        $this->selectedCategories = $product->categories->map(fn ($cat) => [
            'id' => $cat->id,
            'name' => $cat->name,
        ])->all();
        $this->searchCategory = '';
        $this->categoryResults = [];
        $this->showCategoryModal = true;
    }

    #[On('closeCategoryModal')]
    public function closeCategoryModal(): void
    {
        $this->reset(['showCategoryModal', 'editingCategoryProductId', 'editingCategoryProductName', 'selectedCategories', 'searchCategory', 'categoryResults']);
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
        $product = Product::find($this->editingCategoryProductId);

        if (! $product) {
            return;
        }

        $categoryIds = array_column($this->selectedCategories, 'id');
        $product->categories()->sync($categoryIds);

        $this->closeCategoryModal();
        $this->dispatch('productUpdated');
    }
};
?>

<div>
    <table class="w-full" wire:show='showTable'>
        <tr>
            <th>
                Titre
            </th>
            <th>
                description
            </th>
            <th>
                Vendeur
            </th>
            <th class="w-20">
                Actions
            </th>
        </tr>
        @foreach ($products as $product)

            <tr class="bg-blue-400/10 even:bg-blue-400/20">
                <td class="py-1 text-center border-b border-gray-400/50">
                    @if ($editingId === $product->id)
                        <form wire:submit.prevent="update" class="flex items-center justify-center gap-1">
                            <input type="text" wire:model.live="editingName" class="px-2 border rounded text-center" autofocus>
                            <button type="submit" class="text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path fill-rule="evenodd"
                                        d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button type="button" wire:click="cancelEdit" class="text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path
                                        d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                </svg>
                            </button>
                            @error('editingName')
                                <p class='text-xs text-red-500 font-semibold mt-1'>{{ $message }}</p>
                            @enderror
                        </form>
                    @else
                        {{ $product->name }}
                    @endif
                </td>
                <td class="py-1 text-center border-b border-gray-400/50">
                    {{ Str::limit($product->description, 100) }}
                </td>
                <td class="py-1 text-center border-b border-gray-400/50">
                    {{ $product->user->name }}
                </td>
                <td class="py-1 border-b border-gray-400/50">
                    <div class="flex place-content-center gap-1">
                        <a href="edit/{{ $product->name }}" wire:click.prevent='edit({{ $product }})' class="text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentcolor" class="size-5">
                                <path
                                    d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                <path
                                    d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                            </svg>
                        </a>
                        <a href="edit/categories/{{ $product->name }}" wire:click.prevent='openCategoryModal({{ $product }})' class="text-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path
                                    d="M15.993 1.385a1.87 1.87 0 0 1 2.623 2.622l-4.03 5.27a12.749 12.749 0 0 1-4.237 3.562 4.508 4.508 0 0 0-3.188-3.188 12.75 12.75 0 0 1 3.562-4.236l5.27-4.03ZM6 11a3 3 0 0 0-3 3 .5.5 0 0 1-.72.45.75.75 0 0 0-1.035.931A4.001 4.001 0 0 0 9 14.004V14a3.01 3.01 0 0 0-1.66-2.685A2.99 2.99 0 0 0 6 11Z" />
                            </svg>
                        </button>
                        <a href="delete/{{ $product->name }}" wire:confirm='êtes vous sûr de vouloir supprimer la catégorie {{ $product->name }}?'
                            wire:click.prevent='delete({{ $product }})' class="text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentcolor" class="size-5">
                                <path fill-rule="evenodd"
                                    d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>

        @endforeach
    </table>
    <div class="py-2">
        {{ $products->links() }}
    </div>

    <div class="z-50 flex fixed inset-0 bg-black/50" wire:show='showCategoryModal'>
        <div @click.outside="$wire.showCategoryModal = false" class="z-100 m-auto">
            <div class="bg-white p-3 rounded-md border border-black">
                <div class="py-2">
                    <label for="search-category" class="font-semibold">Catégories de "{{ $editingCategoryProductName }}" :</label>
                </div>

                <div class="py-2">
                    <div class="relative">
                        <input type="search" wire:model.live="searchCategory" wire:click="openCategoryDropdown = true"
                            placeholder="Rechercher une catégorie..." id="search-category"
                            class="w-full rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500" />
                        @if (! empty($categoryResults))
                            <ul @click.outside="$wire.openCategoryDropdown = false" x-show="$wire.openCategoryDropdown"
                                class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-md border border-gray-300 bg-white shadow">
                                @foreach ($categoryResults as $categorie)
                                    <li wire:key="edit-autocomplete-category-{{ $categorie['id'] }}" wire:click="selectCategoryForEdit({{ $categorie['id'] }})"
                                        class="cursor-pointer px-3 py-1 hover:bg-gray-100">
                                        {{ $categorie['name'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 min-h-[40px] py-2">
                    @forelse ($selectedCategories as $category)
                        <span wire:key="edit-tag-category-{{ $category['id'] }}"
                            wire:click="removeCategoryFromEdit({{ $category['id'] }})"
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

                <x-submit-button wire:click="saveCategories" color="green">Enregistrer</x-submit-button>
            </div>
        </div>
    </div>
</div>
