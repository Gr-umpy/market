<button wire:click="openModal" type="button" class='cursor-pointer select-none inline-flex items-center px-4 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 leading-5 rounded-md 
    hover:text-indigo-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-stone-100 active:text-gray-800 transition ease-in-out duration-150 
    dark:bg-stone-800 dark:border-gray-600 dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-stone-700 dark:active:text-gray-300 hover:bg-stone-100 
    dark:hover:bg-stone-900 dark:hover:text-indigo-200'>Filtrer</button>
    
<div class="z-50 flex fixed inset-0 bg-black/50" wire:show='showModal'>
    <div class="z-100 m-auto" @click.outside="if (!event.target.closest('.flatpickr-calendar')) $wire.closeModal()">
        <div class="bg-white p-3 rounded-md border border-black">
            {{ $slot }}
        </div>
    </div>
</div>