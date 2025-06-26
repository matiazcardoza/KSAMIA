<div>
    <flux:modal.trigger name="nuevo-tipo-venta">
        <flux:button>Nuevo tipo venta</flux:button>
    </flux:modal.trigger>
    <livewire:Mantenimiento.tipo-venta-nuevo>
    <livewire:Mantenimiento.tipo-venta-editar>

    <flux:modal name="eliminar-tipo-venta" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg"> Esta seguro de eliminar tipo de venta?</flux:heading>
    
                <flux:subheading>
                    <p>Estás a punto de eliminar este tipo de venta.</p>
                    <p>Esta acción no se puede revertir.</p>
                </flux:subheading>
            </div>
    
            <div class="flex gap-2">
                <flux:spacer />
    
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
    
                <flux:button type="submit" variant="danger" wire:click='destroy()'>Eliminar tipo de venta</flux:button>
            </div>
        </div>
    </flux:modal>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-rigth text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tipo_venta as $tventa)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-6 py-2 font-medium text-gray-900 dark:text-white">{{ $tventa->id_tipo_venta }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $tventa->nom_tipo_venta }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $tventa->est_tipo_venta }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            <flux:button size="sm" wire:click='editar({{ $tventa->id_tipo_venta }})'>Editar</flux:button>
                            <flux:button variant="danger" size="sm" wire:click='eliminar({{ $tventa->id_tipo_venta }})'>Eliminar</flux:button>
                        </td>
                    </tr>
                @endforeach
                
            </tbody>
        </table>
    </div>
</div>
