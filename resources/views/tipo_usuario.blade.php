<x-layouts.app :title="__('Tipo Usuario')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Tipo Usuario') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Listado tipo de usuario') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>    
    <livewire:Mantenimiento.tipo_usuario/>
</x-layouts.app>