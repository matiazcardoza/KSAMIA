<x-layouts.app :title="__('Usuarios')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Usuarios') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Listado de usuarios') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>    
    <livewire:Usuarios.usuarios/>
</x-layouts.app>