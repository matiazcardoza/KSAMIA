<x-layouts.app :title="__('Ventas Separadas')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Ventas Separadas') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Listado de ventas separadas') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>    
    <livewire:Separados.separados/>
</x-layouts.app>