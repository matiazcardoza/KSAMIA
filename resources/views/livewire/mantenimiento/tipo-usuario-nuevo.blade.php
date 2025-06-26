<div>
    <div>
        <flux:modal name="nuevo-tipo-usuario" class="md:w-150">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Nuevo Tipo Usuario</flux:heading>
                    <flux:subheading>Agregar detalles de nuevo tipo de usuario, Agreguemos un nuevo tipo de usuario</flux:subheading>
                </div>
    
                <flux:input wire:model='nombre' label="Nombre de tipo de usuario" placeholder="Ingrese nombre" />
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
</div>
