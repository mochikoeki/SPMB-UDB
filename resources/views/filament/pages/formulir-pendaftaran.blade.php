<x-filament-panels::page>
    <x-filament::section>
        {{ $this->form }}
    </x-filament::section>
    <x-filament::section>
        <x-filament::button wire:click="simpan">
            Simpan Pendaftaran
        </x-filament::button>
    </x-filament::section>
</x-filament-panels::page>