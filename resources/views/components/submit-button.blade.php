<button 
    {{ $attributes->merge(
    [ 'type' => 'submit', 'class' => 'cursor-pointer select-none inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border border-gray-300 
    leading-5 rounded-md hover:text-gray-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-800 
    transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-gray-700 
    dark:active:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 dark:hover:text-gray-200' ]) 
    }}> 
    {{ $slot }}
</button>