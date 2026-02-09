<?php

use Livewire\WithPagination;

use Livewire\Attributes\Validate;
use App\Models\Category;
use Livewire\Component;

new class extends Component
{
    use WithPagination;

    public ?int $editingId = null;
    #[Validate('required|min:3|max:255|unique:categories,name')]
    public string $editingName = '';

    public bool $showTable = false;

    protected $listeners = [
        'categoryCreated' => '$refresh',
        'categoryDeleted' => '$refresh',
        'subcategoryCreated' => '$refresh'
    ];

    public function render()
    {
        $categories = Category::with('category')
            ->orderBy('category_id')
            ->latest()
            ->paginate(15);

        $this->showTable = $categories->isNotEmpty();

        return $this->view(['categories' => $categories]);
    }

    public function addSubcategory(Category $category)
    {
        $category_id = $category->id;
        $category_name = $category->name;
        $this->dispatch('OpenSub', $category_id, $category_name);
    }

    public function edit(Category $category)
    {
        $this->editingId = $category->id;
        $this->editingName = $category->name;
    }

    public function update()
    {
        $this->validate();

        Category::find($this->editingId)->update(['name' => $this->editingName]);

        $this->dispatch('categoryUpdated');
        $this->cancelEdit();
    }

    public function cancelEdit()
    {
        $this->editingId = null;
        $this->editingName = '';
    }

    public function delete(Category $category)
    {
        $category->delete();
        $this->dispatch('categoryDeleted');
    }
};
?>
<div>
    <table class="w-full" wire:show='showTable'>
        <tr>
            <th class="w-5/11">
                Titre
            </th>
            <th class="w-5/11">
                Categorie mère
            </th>
            <th class="w-20">
                Actions
            </th>
        </tr>
        @foreach ($categories as $category)

            <tr class="bg-blue-400/10 even:bg-blue-400/20">
                <td class="py-1 text-center border-b border-gray-400/50">
                    @if ($editingId === $category->id)
                        <form wire:submit.prevent="update" class="flex items-center justify-center gap-1">
                            <input type="text" wire:model.live="editingName" 
                                class="px-2 border rounded text-center"
                                autofocus>
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
                        {{ $category->name }}
                    @endif
                </td>
                <td class="py-1 text-center border-b border-gray-400/50">
                    @if ($category->category_id)
                        {{ $category->category->name }}
                    @else
                        Cette catégorie est seulement une catégorie mère
                    @endif
                </td>
                <td class="py-1 border-b border-gray-400/50">
                    <div class="flex place-content-center">
                            <a href="addSubcategory/{{ $category->name }}" wire:click.prevent='addSubcategory({{ $category }})' class="text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                    <path
                                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                </svg>
                            </a>
                        <a href="#" wire:click.prevent='edit({{ $category }})' class="text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentcolor" class="size-5">
                                <path
                                    d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                <path
                                    d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                            </svg>
                        </a>
                        <a href="#" wire:confirm='êtes vous sûr de vouloir supprimer la catégorie {{ $category->name }}?' wire:click.prevent='delete({{ $category }})' class="text-red-700">     
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
        {{ $categories->links() }}
    </div>

    <livewire:category.subcategory.create/>

</div>