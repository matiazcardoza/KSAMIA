
<div class="space-y-4">
    <livewire:Proyectos.ver-lotes.lote-separar>
    <livewire:Proyectos.ver-lotes.lote-vender>
    <div>
        <flux:heading size="lg">Lotes del Proyecto - {{ $nombre }}</flux:heading>
        <flux:subheading>Ver los detalles de proyecto, {{ $descripcion }}</flux:subheading>
    </div>
    @foreach($manzanas as $key => $manzana)
        <div class="space-y-1.5" style="padding: 15px; border: 2px solid #8369e0; background: #13fcf0; border-radius: 10px;">

            <flux:heading size="lg">Manzana - {{ $manzana['nombreMz'] }}</flux:heading>
            <flux:subheading>{{ $manzana['descripcionMz'] }}</flux:subheading>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left rtl:text-rigth text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">n° lote</th>
                            <th scope="col" class="px-6 py-3">Área (m²)</th>
                            <th scope="col" class="px-6 py-3">precio</th>
                            <th scope="col" class="px-6 py-3">Estado</th>
                            <th scope="col" class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($manzana['lotes'] as $loteKey => $lote)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-2 font-medium text-gray-900 dark:text-white">{{ $lote['numLote'] }}</td>
                                <td class="px-2 text-gray-600 dark:text-gray-300">{{ $lote['areaLote'] }}</td>
                                <td class="px-2 text-gray-600 dark:text-gray-300">{{ $lote['precioLote'] }}</td>
                                <td class="px-2 text-gray-600 dark:text-gray-300">
                                    @if ($lote['estado'] == 2)
                                        Vendido
                                    @else
                                        Disponible
                                    @endif
                                </td>
                                <td class="px-2 text-gray-600 dark:text-gray-300">
                                    <flux:button variant="danger" size="sm" wire:click="vender({{ $lote['id_lote'] }}, {{ $id_proyecto }})">Vender</flux:button>
                                    <flux:button size="sm" wire:click="separar({{ $lote['id_lote'] }})">Separar</flux:button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach        
</div>
