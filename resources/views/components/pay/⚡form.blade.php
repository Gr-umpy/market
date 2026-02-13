<?php

use Livewire\Component;

use Livewire\Attributes\Validate;

new class extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    #[Validate('string|min:10|max:10')]
    public string $phone = '';
    public string $address = '';
    public string $address_2 = '';
    public string $city = '';
    public string $postal_code = '';
    public string $country = '';
    public string $password = '';
    public bool $save_address = false;

    public function mount(): void
    {
        if ($user = auth()->user()) {
            $this->first_name = $user->first_name ?? '';
            $this->last_name = $user->last_name ?? '';
            $this->email = $user->email ?? '';
            $this->phone = $user->phone ?? '';
            $this->address = $user->address ?? '';
            $this->address_2 = $user->address_2 ?? '';
            $this->city = $user->city ?? '';
            $this->postal_code = $user->postal_code ?? '';
            $this->country = $user->country ?? '';
        }
    }
    
    public function save() {
      if ($this->save_address) {
        auth()->user()->update([
            'phone' => $this->phone,
            'address' => $this->address,
            'address_2' => $this->address_2,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
        ]);
      }
    }
};
?>

<div>
    <form wire:submit="save">
        <div class="space-y-12">
            <div class="border-b border-white/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        
                    <div class="sm:col-span-2">
                        <label for="last_name" class="block text-sm/6 font-medium text-black">Nom</label>
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="last_name" type="text" wire:model="last_name" placeholder="Votre nom"
                                    value="{{ old('last_name') }}"
                                    class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                            </div>
                            @error('last_name')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="first_name" class="block text-sm/6 font-medium text-black">Prénom</label>
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="first_name" type="text" wire:model="first_name" placeholder="Votre prénom"
                                    value="{{ old('first_name') }}"
                                    class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                            </div>
                            @error('first_name')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="phone" class="block text-sm/6 font-medium text-black">Téléphone</label>
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="phone" type="tel" wire:model="phone" placeholder="06 00 00 00 00"
                                    value="{{ old('phone') }}"
                                    class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                            </div>
                            @error('phone')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-4">
                        <label for="address" class="block text-sm/6 font-medium text-black">Adresse</label>
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="address" type="text" wire:model="address" placeholder="4 Rue Bonaparte"
                                    value="{{ old('address') }}"
                                    class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                            </div>
                            @error('address')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="address_2" class="block text-sm/6 font-medium text-black">Complément d'adresse</label>
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input id="address_2" type="text" wire:model="address_2" placeholder="Appartement, bâtiment..."
                                    value="{{ old('address_2') }}"
                                    class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                            </div>
                            @error('address_2')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="country" class="block text-sm/6 font-medium text-black">Pays</label>
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="country" type="text" wire:model="country" placeholder="France"
                                    value="{{ old('country') }}"
                                    class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                            </div>
                            @error('country')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="postal_code" class="block text-sm/6 font-medium text-black">Code postal</label>
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="postal_code" type="text" wire:model="postal_code" placeholder="20000"
                                    value="{{ old('postal_code') }}"
                                    class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                            </div>
                            @error('postal_code')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="city" class="block text-sm/6 font-medium text-black">Ville</label>
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="city" type="text" wire:model="city" placeholder="Ajaccio"
                                    value="{{ old('city') }}"
                                    class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                            </div>
                            @error('city')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm/6 font-medium text-black">Email</label>
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-grey-200 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500">
                                <input required id="email" type="email" wire:model="email" placeholder="exemple@exemple.com"
                                    value="{{ old('email') }}"
                                    class="shrink-0 text-base sm:text-sm block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-black placeholder:text-gray-500 focus:outline-none" />
                            </div>
                            @error('email')
                                <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @auth
                    <div class="sm:col-span-2">
                        <div class="flex items-center gap-x-3">
                            <input id="save_address" type="checkbox" wire:model="save_address"
                                class="h-4 w-4" />
                            <label for="save_address" class="block text-sm/6 font-medium text-black">
                                Se souvenir de mon adresse
                            </label>
                        </div>
                    </div>
                    @endauth

                </div>
            </div>
        </div>

        <x-submit-button color="teal" class="">payer</x-submit-button>

    </form>
</div>