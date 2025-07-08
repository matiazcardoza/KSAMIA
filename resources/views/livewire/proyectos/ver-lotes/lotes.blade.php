
<div class="space-y-4">
    <livewire:Proyectos.ver-lotes.lote-separar>
    <livewire:Proyectos.ver-lotes.lote-vender>
    <livewire:Proyectos.ver-lotes.lote-editar>
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
                                    @if ($lote['estadoVenta'] == 2)
                                        Vendido
                                    @else
                                        Disponible
                                    @endif
                                </td>
                                @if ($lote['estadoVenta'] == 1 || $lote['estadoVenta'] == null)
                                    <td class="px-2 text-gray-600 dark:text-gray-300">
                                        <flux:button variant="danger" size="sm" wire:click="vender({{ $lote['id_lote'] }}, {{ $id_proyecto }})">Vender</flux:button>
                                        <flux:button size="sm" wire:click="separar({{ $lote['id_lote'] }})">Separar</flux:button>
                                    </td>
                                @else   
                                    <td class="px-2 font-medium text-gray-900 dark:text-white flex items-center gap-1">
                                        <flux:button variant="filled" size="sm" wire:click="editar({{ $lote['id_lote'] }}, {{ $id_proyecto }})">Editar</flux:button>
                                        <flux:button size="sm" variant="ghost" title="Ver lote">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0c0 5-4.03 9-9 9S3 17 3 12 7.03 3 12 3s9 4.03 9 9z" />
                                            </svg>
                                        </flux:button>
                                        <flux:button size="sm" variant="filled" title="Descargar documento de contrato" 
                                                    onclick="window.open('{{ route('contrato.pdf', $lote['id_lote']) }}', '_blank')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </flux:button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach        
</div>