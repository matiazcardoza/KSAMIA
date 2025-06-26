<x-layouts.app :title="__('Proyectos')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Proyectos') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Listado de proyectos') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>    
    <livewire:dark-mode-toogle />
    <livewire:Proyectos.proyectos/>
</x-layouts.app>
