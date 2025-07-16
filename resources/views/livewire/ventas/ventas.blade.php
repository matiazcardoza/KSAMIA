<div>
    <flux:modal name="eliminar-venta" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg"> Esta seguro de eliminar Venta?</flux:heading>

                <flux:subheading>
                    <p>Estás a punto de eliminar esta venta.</p>
                    <p>Esta acción no se puede revertir, el estado de lote se reiniciara.</p>
                </flux:subheading>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger" wire:click='destroy()'>Eliminar proyecto</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="ver-venta" class="sm:w-150">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg"> Estos son los detalles de la venta.</flux:heading>
            </div>
            <div>
                <flux:subheading>Detalles de la venta</flux:subheading>
                <p><strong>ID Venta:</strong> {{ $venta->id_venta ?? 'N/A' }}</p>
                <p><strong>Proyecto:</strong> {{ $venta->nom_proyecto ?? 'N/A' }}</p>
                <p><strong>Manzana:</strong> {{ $venta->nom_manzana ?? 'N/A' }}</p>
                <p><strong>Lote:</strong> {{ $venta->nom_lote ?? 'N/A' }}</p>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cerrar</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

    <livewire:Proyectos.ver-lotes.lote-editar>
    <livewire:Proyectos.ver-lotes.lote-separar-editar>

    @foreach ($proyecto as $dato)
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Ventas por ') . $dato->nom_proyecto }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Listado de ventas por ') . $dato->nom_proyecto }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
    @endforeach

    <div class="flex gap-2 mb-4">
        <flux:button size="sm" wire:click="filtrarTodos" variant="{{ $filtro_activo == 'todos' ? 'primary' : 'outline' }}">
            Todos
        </flux:button>
        
        <flux:button size="sm" wire:click="filtrarPorEscritura" variant="{{ $filtro_activo == 'escritura' ? 'primary' : 'outline' }}">
            Por Escritura
        </flux:button>
        
        <flux:button size="sm" wire:click="filtrarPorCuota" variant="{{ $filtro_activo == 'cuota' ? 'primary' : 'outline' }}">
            Por Cuota
        </flux:button>
        
        <flux:button size="sm" wire:click="filtrarSeparados" variant="{{ $filtro_activo == 'separados' ? 'primary' : 'outline' }}">
            Separados
        </flux:button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-rigth text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Proyecto</th>
                    <th scope="col" class="px-6 py-3">Manzana</th>
                    <th scope="col" class="px-6 py-3">Lote</th>
                    <th scope="col" class="px-6 py-3">Estado</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ventas as $value)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-6 py-2 font-medium text-gray-900 dark:text-white">{{ $value->id_venta }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $value->nom_proyecto }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $value->nom_manzana }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $value->nom_lote }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            @if ($value->est_venta == 2)
                                <span class="text-blue-500">VENDIDA</span>
                            @else
                                <span class="text-blue-500">SEPARADO</span>
                            @endif
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            @if ($value->est_venta == 2)
                                <flux:button size="sm" wire:click='editar({{ $value->id_lote }}, {{ $value->id_proyecto }})'>Editar</flux:button>
                            @else
                                <flux:button size="sm" wire:click='separarEditar({{ $value->id_lote }})'>Editar</flux:button>
                            @endif
                            <flux:button variant="danger" size="sm" wire:click='eliminar({{ $value->id_venta }})'>Eliminar</flux:button>
                            <flux:button variant="ghost" size="sm" title="Ver lote" wire:click='verVenta({{ $value->id_venta }})'>
                                <x-heroicon-s-eye class="w-6 h-6 text-gray-600" />
                            </flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
