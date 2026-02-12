<x-layout>
    <x-slot:heading>
        Panier
    </x-slot:heading>
    <ul>
        @foreach (session()->except(['_token', '_flash', '_previous']) as $key => $value)
            <li class="py-2">
                clé {{ $key }}, valeur {{ $value }}
                {{-- {{ session()->forget($key) }} --}}
            </li>
        @endforeach
    </ul>
</x-layout>