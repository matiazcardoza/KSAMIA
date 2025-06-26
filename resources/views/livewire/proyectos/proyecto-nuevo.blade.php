<div>
    <flux:modal name="nuevo-proyecto" class="md:w-150">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">Crear Nuevo Proyecto</flux:heading>
                <flux:subheading>Agregar detalles de nuevo proyecto, Agreguemos un nuevo proyecto</flux:subheading>
            </div>

            <div style="display: flex; gap: 20px; width: 100%;">
                <flux:input wire:model='nombre' label="Nombre del proyecto" placeholder="Ej: urbanizacion" style="flex: 2;" />
                <flux:input wire:model='ubicacion' label="Ubicación" placeholder="Ej: Av.principal 123" style="flex: 3;" />
            </div>
            
            <div style="display: flex; gap: 20px; width: 100%;">
                <flux:textarea wire:model='descripcion' label="Descripcion" placeholder="Descripcion general del proyecto" />
                <flux:input wire:model='presupuesto' type="number" label="Presupuesto de proyecto" placeholder="Presupuesto" />
            </div>


            @foreach($manzanas as $key => $manzana)
                <div class="space-y-1.5" style="padding: 15px; border: 2px solid #8369e0; background: #13fcf0; border-radius: 10px;">
                    <div style="display: flex; gap: 20px; width: 100%;">
                        <flux:input size="sm" wire:model="manzanas.{{ $key }}.nombreMz" label="Nombre Manzana" placeholder="A" />
                        <flux:textarea size="sm" wire:model="manzanas.{{ $key }}.descripcionMz" label="Descripcion Manzana" placeholder="Descripcion Manzana" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($manzana['lotes'] as $loteKey => $lote)
                            <div class="space-y-1.5 p-3 border-2 border-green-500 bg-blue-200 rounded-lg">
                                <div class="grid grid-cols-1 gap-2">
                                    <flux:input size="sm" wire:model="manzanas.{{ $key }}.lotes.{{ $loteKey }}.numLote" type="number" placeholder="Nro de Lote" />
                                    <flux:input size="sm" wire:model="manzanas.{{ $key }}.lotes.{{ $loteKey }}.areaLote" type="number" placeholder="Área (m²)" />
                                    <flux:input size="sm" wire:model="manzanas.{{ $key }}.lotes.{{ $loteKey }}.precioLote" type="number" placeholder="Precio" />
                                    <flux:button size="sm" variant="danger" wire:click="eliminarLote({{ $key }}, {{ $loteKey }})">Eliminar Lote</flux:button>
                                </div>
                            </div>
                        @endforeach    
                    </div>
                    
                    <flux:button size="sm" variant="filled" wire:click="agregarLote({{ $key }})">Agregar Lote</flux:button>
                    <flux:button size="sm" variant="danger" wire:click="eliminarManzana({{ $key }})">Eliminar Manzana</flux:button>
                </div>
            @endforeach

            <flux:input wire:model='fecha' label="Fecha" type="date" />
            <flux:input wire:model='pdfPlano' type="file" label="Seleccione plano de proyecto" />

            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="flex">
                <flux:spacer />
                <flux:button variant="filled" wire:click='agregarmanzana'>Agregar Manzana</flux:button>
                <flux:button type="submit" variant="primary" wire:click='submit'>Guardar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>


