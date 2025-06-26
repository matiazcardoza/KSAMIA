<div>
    <flux:modal name="nuevo-tipo-venta" class="md:w-150">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Nuevo tipo venta</flux:heading>
                <flux:subheading>Agregar detalles de nuevo tipo de venta, Agreguemos un nuevo tipo de venta</flux:subheading>
            </div>

            <flux:input wire:model='nombre' label="Nombre de tipo de venta" placeholder="Ingrese nombre" />
            <flux:checkbox.group label="Estado">
                <flux:checkbox wire:model='estado' label="Activo" checked />
            </flux:checkbox.group>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click='submit'>Guardar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
