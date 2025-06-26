<div>
    <flux:modal name="separar-lote" class="md:w-150">
        <div class="space-y-5">
            <div class="space-y-4">
                <flux:heading size="lg">Separar Lote este es el id - {{ $id_lote }}</flux:heading>
                <flux:subheading>ver detalles de lote, separar un lote</flux:subheading>
            </div>

            <div class="space-y-3">
                <flux:input size="sm" label="N° Documento de identidad" type="number" wire:model='dniCliente' wire:input.debounce.300ms='buscarCliente' oninput="this.value = this.value.slice(0, 8)" />
                <flux:input size="sm" label="Nombre de cliente" wire:model='nomCliente' />
                <flux:input size="sm" label="Apellido de cliente" wire:model='apeCliente' />
                <flux:input size="sm" label="Correo Electrónico" wire:model='emailCliente' />
                <flux:input size="sm" label="Teléfono" type="number" wire:model='telCliente' />
                <flux:input size="sm" label="Direción" wire:model='dirCliente' />
                <flux:input size="sm" label="Fecha de separación" type="date" wire:model='fechaSeparar' />
                <flux:input size="sm" label="Monto de separación" type="number" wire:model='montoSeparar' />
            </div>
            <div>
                <flux:button size="sm" variant="primary" wire:click='separar' >Separar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
