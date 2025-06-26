<div>
    <flux:modal name="editar-tipo-venta" class="md:w-150">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Editar tipo venta</flux:heading>
                <flux:subheading>Modificar detalles tipo de venta, Modifiquemos un tipo de venta</flux:subheading>
            </div>

            <flux:input wire:model='nombre' label="Nombre de tipo de venta" placeholder="Ingrese nombre" />
            <flux:checkbox.group label="Estado">
                <flux:checkbox wire:model='estado' label="Activo" checked />
            </flux:checkbox.group>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click='update'>Guardar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
