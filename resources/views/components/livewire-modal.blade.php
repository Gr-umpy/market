<div class="z-50 flex fixed inset-0 bg-black/50" wire:show='showModal'>
    <div id="Dialog-edit-task" @click.outside="if (!event.target.closest('.flatpickr-calendar')) $wire.showModal = false" class="z-100 m-auto">
        <div class="bg-white p-3 rounded-md border border-black">
            {{ $slot }}
        </div>
    </div>
</div>