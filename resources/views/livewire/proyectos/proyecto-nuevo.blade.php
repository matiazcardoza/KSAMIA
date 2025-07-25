<div>
    <flux:modal name="nuevo-proyecto" class="max-w-3xl w-full">
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
            </div>

            <div style="display: flex; gap: 20px; width: 100%; align-items: end;">
                <div>
                    <label>
                        <input type="checkbox" wire:model.live="usarDolar" />
                        ¿Presupuesto en dólares?
                    </label>
                </div>
                @if(!$usarDolar)
                    <flux:input wire:model.live='presupuesto' type="number" label="Presupuesto de proyecto (S/)" placeholder="Presupuesto en soles" />
                @else
                    <flux:input wire:model.live='presuDolar' type="number" label="Presupuesto (US$)" placeholder="Presupuesto en dólares" step="0.01" />
                @endif
            </div>

            @foreach($inversores as $key => $inversor)
                <div class="space-y-2 p-4 border-2 border-yellow-400 bg-yellow-100 rounded-lg mb-3">
                   <div class="flex justify-between items-center">
                        <flux:heading >Inversor {{ $key + 1 }}</flux:heading>
                        <flux:button variant="danger" wire:click="eliminarInversor({{ $key }})">
                            Eliminar
                        </flux:button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <flux:input size="sm" wire:model="inversores.{{ $key }}.nombreInversor" 
                                   label="Nombre completo" placeholder="Nombre del inversor" />
                        <flux:input size="sm" wire:model="inversores.{{ $key }}.emailInversor" 
                                   label="Email" placeholder="correo@ejemplo.com" type="email" />
                        <flux:input size="sm" wire:model="inversores.{{ $key }}.telInversor" 
                                   label="Teléfono" placeholder="999-999-999" />
                        <flux:input size="sm" wire:model.live="inversores.{{ $key }}.montoInversor" 
                                   label="Monto de inversión" placeholder="10000.00" type="number" step="0.01" />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <flux:input size="sm" wire:model="inversores.{{ $key }}.porcentajeInversor" 
                                   label="Porcentaje (%)" placeholder="25.5" type="number" 
                                     step="0.01" min="0" max="100" />
                        <flux:input size="sm" wire:model="inversores.{{ $key }}.fechaInversor" 
                                   label="Fecha de ingreso" type="date" />
                    </div>
                </div>
            @endforeach

            <div style="display: flex; gap: 20px; width: 100%;">
                <flux:button size="sm" variant="filled" wire:click='agregarinversor'>Agregar inversor</flux:button>
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

            <div>
                <flux:input wire:model='fecha' label="Fecha" type="date" />
                <flux:input wire:model='pdfPlano' type="file" label="Seleccione plano de proyecto" />
            </div>
            
            <div>
                <flux:input wire:model="evidencias" type="file" label="Adjuntar evidencias" multiple />
            </div>

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


