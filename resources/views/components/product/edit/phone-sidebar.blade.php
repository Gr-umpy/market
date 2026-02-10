@props(['product'])

<x-slot:button>
    <button command="show-modal" commandfor="drawer"
        class="md:hidden rounded-md px-2.5 py-1.5 text-sm font-semibold text-black inset-ring inset-ring-white/5 hover:bg-white/20">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path fill-rule="evenodd"
                d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z"
                clip-rule="evenodd" />
        </svg>
    </button>
</x-slot:button>

<div class="md:hidden" x-data="{ show: 1, isAnyDirty: false, dirtyStates: {1: false, 2: false, 3: false} }" x-init="
        window.isAnyDirty = false;
        Livewire.on('editInfoDirty', (isDirty) => { dirtyStates[1] = isDirty[0]; isAnyDirty = Object.values(dirtyStates).some(v => v); window.isAnyDirty = isAnyDirty; });
        Livewire.on('editCategoryDirty', (isDirty) => { dirtyStates[2] = isDirty[0]; isAnyDirty = Object.values(dirtyStates).some(v => v); window.isAnyDirty = isAnyDirty; });
        Livewire.on('editVariantDirty', (isDirty) => { dirtyStates[3] = isDirty[0]; isAnyDirty = Object.values(dirtyStates).some(v => v); window.isAnyDirty = isAnyDirty; });
    ">
    <el-dialog>
        <dialog id="drawer" aria-labelledby="drawer-title"
            class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent not-open:hidden backdrop:bg-transparent">
            <el-dialog-backdrop
                class="absolute inset-0 bg-gray-900/50 transition-opacity duration-500 ease-in-out data-closed:opacity-0"></el-dialog-backdrop>

            <div tabindex="0" class="absolute inset-0 pl-10 focus:outline-none sm:pl-16">
                <el-dialog-panel
                    class="group/dialog-panel relative ml-auto block size-full max-w-md transform transition duration-500 ease-in-out data-closed:translate-x-full sm:duration-700">
                    <!-- Close button, show/hide based on slide-over state. -->
                    <div
                        class="absolute top-0 left-0 -ml-8 flex pt-4 pr-2 duration-500 ease-in-out group-data-closed/dialog-panel:opacity-0 sm:-ml-10 sm:pr-4">
                        <button type="button" command="close" commandfor="drawer"
                            class="relative rounded-md text-teal-400 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                            <span class="absolute -inset-2.5"></span>
                            <span class="sr-only">Close panel</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                data-slot="icon" aria-hidden="true" class="size-6">
                                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    <div
                        class="relative flex h-full flex-col overflow-y-auto bg-gray-600 py-6 shadow-xl after:absolute after:inset-y-0 after:left-0 after:w-px after:bg-white/10">
                        <div class="px-4 sm:px-6">
                            <h2 id="drawer-title" class="text-base font-semibold text-white">Édition</h2>
                        </div>
                        <div class="relative mt-6 flex-1 px-4 sm:px-6">
                            <div class="py-0.5">
                                <x-button color="green" x-on:click="show = 1">Éditer les informations du
                                    produit</x-button>
                            </div>
                            <div class="py-0.5">
                                <x-button color="green" x-on:click="show = 2">Éditer les catégories du
                                    produit</x-button>
                            </div>
                            <div class="py-0.5">
                                <x-button color="green" x-on:click="show = 3">Éditer les Prix du produit</x-button>
                            </div>
                        </div>
                    </div>
                </el-dialog-panel>
            </div>
        </dialog>
    </el-dialog>

    <div x-show="show == 1">
        <livewire:product.edit.edit-info :$product />
    </div>
    <div x-show="show == 2">
        <livewire:product.edit.edit-category :$product />
    </div>
    <div x-show="show == 3">
        <livewire:product.edit.edit-variant :$product />
    </div>
</div>

<script>
    window.addEventListener('beforeunload', (e) => {
        if (window.isAnyDirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
</script>