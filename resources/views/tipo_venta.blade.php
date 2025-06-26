<x-layouts.app :title="__('Tipo Venta')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Tipo Venta') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Listado tipo de venta') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>    
    <livewire:Mantenimiento.tipo_venta/>
</x-layouts.app>