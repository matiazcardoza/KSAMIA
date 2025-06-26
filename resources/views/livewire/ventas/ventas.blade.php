<div>
    @foreach ($proyecto as $dato)
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Ventas por ') . $dato->nom_proyecto }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Listado de ventas por ') . $dato->nom_proyecto }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
    @endforeach

    <flux:modal.trigger name="nueva-venta">
        <flux:button>Nueva Venta</flux:button>
    </flux:modal.trigger>

    <div class="flex gap-2 mb-4">
        <flux:button size="sm"  >Todos</flux:button>
        <flux:button size="sm"  >Por Escritura</flux:button>
        <flux:button size="sm"  >Por Cuota</flux:button>
        <flux:button size="sm"  >Separados</flux:button>
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
                            @if ($value->est_lote == 2)
                                <span class="text-blue-500">VENDIDA</span>
                            @else
                                <span class="text-blue-500">SEPARADO</span>
                            @endif
                        </td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            <flux:button size="sm" wire:click='editar({{ $value->id_tipo_venta }})'>Editar</flux:button>
                            <flux:button variant="danger" size="sm" wire:click='eliminar({{ $value->id_tipo_venta }})'>Eliminar</flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
