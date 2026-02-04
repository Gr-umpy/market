@props(['active' => false])

<a class="{{ $active ? 'bg-gray-900' : 'hover:bg-gray-800' }}
 cursor-pointer select-none rounded-md px-3 py-2 text-sm font-medium text-white" aria-current="{{ $active ? 'page' : 'false' }}"
    {{ $attributes }}>
    {{ $slot }}
</a>