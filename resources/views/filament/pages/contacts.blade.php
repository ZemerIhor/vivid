<x-filament::page>
    <form wire:submit.prevent="save" class="space-y-6">
        {{ $this->form }}

        <div>
            <button type="submit" class="filament-button filament-button-primary">
                Зберегти
            </button>
        </div>
    </form>
</x-filament::page>
