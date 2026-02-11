@props(['product'])

<div class="grid grid-cols-3">
    <div class="relative mt-6 flex-1 px-4 sm:px-6 max-md:hidden">
        <div class="py-0.5">
            <a href="{{ route('products.edit.infos', $product) }}" class="cursor-pointer select-none inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border 
                        border-gray-300 leading-5 rounded-md hover:text-green-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 
                        active:bg-green-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-green-800 dark:border-gray-600 
                        dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-green-700 dark:active:text-gray-300 hover:bg-green-100 
                        dark:hover:bg-green-900 dark:hover:text-green-200">

                Éditer les informations du produit

            </a>
        </div>
        <div class="py-0.5">
            <a href="{{ route('products.edit.categories', $product) }}" class="cursor-pointer select-none inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border 
                    border-gray-300 leading-5 rounded-md hover:text-green-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 
                    active:bg-green-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-green-800 dark:border-gray-600 
                    dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-green-700 dark:active:text-gray-300 hover:bg-green-100 
                    dark:hover:bg-green-900 dark:hover:text-green-200">

                Éditer les catégories du produit

            </a>
        </div>
        <div class="py-0.5">
            <a href="{{ route('products.edit.variants', $product) }}" class="cursor-pointer select-none inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 bg-white border 
                    border-gray-300 leading-5 rounded-md hover:text-green-700 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 
                    active:bg-green-100 active:text-gray-800 transition ease-in-out duration-150 dark:bg-green-800 dark:border-gray-600 
                    dark:text-gray-200 dark:focus:border-blue-700 dark:active:bg-green-700 dark:active:text-gray-300 hover:bg-green-100 
                    dark:hover:bg-green-900 dark:hover:text-green-200">

                Éditer les Prix du produit

            </a>
        </div>
    </div>
    <div class="md:col-span-2 max-md:col-span-3">
        {{ $slot }}
    </div>
</div>