<div>
    <flux:modal name="vender-lote" class="md:w-150">
        <div class="space-y-5">
            <div class="space-y-4">
                <flux:heading size="lg">Vender Lote con id - {{ $id_lote }}</flux:heading>
                <flux:subheading>Ver detalles de lote, separar un lote</flux:subheading>
            </div>

            <div class="space-y-3">
                <flux:input size="sm" label="N° Documento de identidad" type="number" wire:model='dniCliente' wire:input.debounce.300ms='buscarCliente' oninput="this.value = this.value.slice(0, 8)" />
                <flux:input size="sm" label="Nombre de cliente" style="text-transform: uppercase;" wire:model='nomCliente' />
                <flux:input size="sm" label="Apellido de cliente" style="text-transform: uppercase;" wire:model='apeCliente' />
                <flux:input size="sm" label="Correo Electrónico" wire:model='emailCliente' />
                <flux:input size="sm" label="Teléfono" type="number" wire:model='telCliente' />
                <flux:input size="sm" label="Direción" style="text-transform: uppercase;" wire:model='dirCliente' />
                <flux:input size="sm" label="Fecha de Venta" type="date" wire:model='fechaVenta' />

                <flux:select size="sm" label="Asesor" wire:model='asesorVenta'>
                    <option>Seleccine Asesor</option>
                    @foreach($asesor_venta as $asesor)
                        <option value="{{ $asesor['id'] }}">{{ $asesor['label'] }}</option>
                    @endforeach
                </flux:select>
                
                <flux:select size="sm" label="Tipo de venta" wire:model.live='tipoVenta'>
                    @foreach($tipo_venta as $tipo)
                        <option value="{{ $tipo['id'] }}">{{ $tipo['label'] }}</option>
                    @endforeach
                </flux:select>

                <flux:input size="sm" label="Precio Lote" type="number" wire:model='precioVenta' />
                @if($tipoVenta == 2)
                    <flux:input size="sm" label="Cantidad de Cuotas" type="number" wire:model="cuotaVenta" />
                @endif
            </div>

            <div class="space-y-3 mt-4">
                <flux:heading size="lg">Plano del Lote</flux:heading>
                <p class="text-xs text-gray-500">URL del PDF: {{ $pdfUrl }}</p>

                
                @if($pdfUrl)
                     <div class="border rounded-lg p-2 bg-gray-50">
        <iframe src="{{ $pdfUrl }}" width="100%" height="500px" class="border-0"></iframe>
        <div class="flex gap-2 mt-2">
            <button id="btn-color-rojo" class="px-3 py-1 bg-red-500 text-white rounded text-sm">Marcar Rojo</button>
            <button id="btn-color-verde" class="px-3 py-1 bg-green-500 text-white rounded text-sm">Marcar Verde</button>
            <button id="btn-guardar-pdf" class="px-3 py-1 bg-blue-500 text-white rounded text-sm">Guardar Cambios</button>
        </div>
    </div>
                @else
                    <p>No hay plano disponible para este lote.</p>
                @endif
            </div>


            <div>
                <flux:button size="sm" variant="primary" wire:click='vender'>Vender</flux:button>
            </div>
        </div>
    </flux:modal>
    @if($pdfUrl)
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {  console.log("se");
            // Código para inicializar PDF.js y fabric.js cuando se muestre el modal
            window.addEventListener('flux:modal-shown', event => {
              
                
                if (event.detail.name === 'vender-lote') {
                    initPdfEditor();
                }
            });
            
            function initPdfEditor() {
                const pdfUrl = '{{ $pdfUrl }}';
                const pdfjsLib = window['pdfjs-dist/build/pdf'];
                pdfjsLib.GlobalWorkerOptions.workerSrc = '//cdn.jsdelivr.net/npm/pdfjs-dist@3.4.120/build/pdf.worker.min.js';
                
                // Cargar el PDF
                pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                    // Por simplicidad, trabajamos con la primera página
                    pdf.getPage(1).then(function(page) {
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale });
                        
                        // Preparar el canvas para PDF.js
                        const canvas = document.getElementById('pdf-canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        
                        // Renderizar el PDF en el canvas
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        
                        page.render(renderContext).promise.then(function() {
                            // Inicializar fabric.js para edición
                            const editCanvas = document.getElementById('edit-canvas');
                            editCanvas.width = viewport.width;
                            editCanvas.height = viewport.height;
                            
                            const fabricCanvas = new fabric.Canvas('edit-canvas');
                            
                            // Botones para colorear
                            document.getElementById('btn-color-rojo').addEventListener('click', function() {
                                enableDrawing(fabricCanvas, 'red');
                            });
                            
                            document.getElementById('btn-color-verde').addEventListener('click', function() {
                                enableDrawing(fabricCanvas, 'green');
                            });
                            
                            // Botón para guardar
                            document.getElementById('btn-guardar-pdf').addEventListener('click', function() {
                                // Combinar los canvas
                                const mergedCanvas = document.createElement('canvas');
                                mergedCanvas.width = canvas.width;
                                mergedCanvas.height = canvas.height;
                                const mergedContext = mergedCanvas.getContext('2d');
                                
                                // Dibujar el PDF original
                                mergedContext.drawImage(canvas, 0, 0);
                                
                                // Dibujar las anotaciones
                                mergedContext.drawImage(editCanvas, 0, 0);
                                
                                // Convertir a data URL y enviar al backend
                                const dataUrl = mergedCanvas.toDataURL('image/png');
                                
                                // Llamar al método Livewire para guardar
                                @this.call('guardarPDFModificado', dataUrl);
                            });
                        });
                    });
                });
            }
            
            function enableDrawing(canvas, color) {
                canvas.isDrawingMode = true;
                canvas.freeDrawingBrush.color = color;
                canvas.freeDrawingBrush.width = 5;
            }
        });
    </script>
    @endif
</div>