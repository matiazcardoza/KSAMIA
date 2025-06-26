<div>
    <flux:modal name="editar-tipo-usuario" class="md:w-150">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Editar tipo usuario</flux:heading>
                <flux:subheading>Modificar detalles tipo de usuario, Modifiquemos un tipo de usuario</flux:subheading>
            </div>

            <flux:input wire:model='nombre' label="Nombre de tipo de usuario" placeholder="Ingrese nombre" />
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
