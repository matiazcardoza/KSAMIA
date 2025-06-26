<div>
    <!-- Cargar librerías necesarias -->
    @once
    <style>
            #pdf-container {
                max-height: 600px;
                overflow-y: auto;
                border: 1px solid #e5e7eb;
                position: relative;
            }
            .canvas-container {
                margin: 0 auto !important;
            }
            .color-picker-active {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
            }
        </style>
    @endonce

    <flux:modal name="vender-lote" class="max-w-7xl w-full">
        <div class="space-y-5">
            <div class="space-y-4">
                <flux:heading size="lg">Vender Lote con id - {{ $id_lote }}</flux:heading>
                <flux:subheading>Ver detalles de lote, separar un lote</flux:subheading>
            </div>
            
            <!-- Datos personales y de contacto en una sola fila -->
            <div class="flex flex-row gap-4 flex-wrap w-full">
                <flux:input size="sm" label="N° Documento de identidad" type="number" wire:model='dniCliente' wire:input.debounce.300ms='buscarCliente' oninput="this.value = this.value.slice(0, 8)" class="flex-1 min-w-[180px]" />
                <flux:input size="sm" label="Nombre de cliente" style="text-transform: uppercase;" wire:model='nomCliente' class="flex-1 min-w-[180px]" />
                <flux:input size="sm" label="Apellido de cliente" style="text-transform: uppercase;" wire:model='apeCliente' class="flex-1 min-w-[180px]" />
                <flux:input size="sm" label="Correo Electrónico" wire:model='emailCliente' class="flex-1 min-w-[180px]" />
                <flux:input size="sm" label="Teléfono" type="number" wire:model='telCliente' class="flex-1 min-w-[180px]" />
                <flux:input size="sm" label="Direción" style="text-transform: uppercase;" wire:model='dirCliente' class="flex-1 min-w-[180px]" />
                <flux:input size="sm" label="Fecha de Venta" type="date" wire:model='fechaVenta' class="flex-1 min-w-[180px]" />
                <flux:select size="sm" label="Asesor" wire:model='asesorVenta' class="flex-1 min-w-[180px]">
                    <option>Seleccine Asesor</option>
                    @foreach($asesor_venta as $asesor)
                        <option value="{{ $asesor['id'] }}">{{ $asesor['label'] }}</option>
                    @endforeach
                </flux:select>
                <flux:select size="sm" label="Tipo de venta" wire:model.live='tipoVenta' class="flex-1 min-w-[180px]">
                    @foreach($tipo_venta as $tipo)
                        <option value="{{ $tipo['id'] }}">{{ $tipo['label'] }}</option>
                    @endforeach
                </flux:select>
                <flux:input size="sm" label="Precio Lote" type="number" wire:model.live='precioVenta' class="flex-1 min-w-[180px]" />
                @if($tipoVenta == 2)
                    <flux:input size="sm" label="Cantidad de Cuotas" type="number" wire:model.live="cuotaVenta" class="flex-1 min-w-[180px]" />
                @endif
            </div>

            @if($tipoVenta == 2 && !empty($cuotas))
                <div class="w-full mt-2">
                    <div class="flex flex-row gap-2 flex-wrap">
                        @foreach($cuotas as $i => $valor)
                            <div class="bg-blue-100 rounded p-2 text-center font-semibold min-w-[120px]">
                                Cuota {{ $i + 1 }}: S/ {{ $valor }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Editor de PDF -->
            <div class="space-y-3 mt-4">
                <div class="flex justify-between items-center">
                    <flux:heading size="lg">Plano del Lote</flux:heading>
                    <div class="flex gap-2">
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.clearAll()">Limpiar Todo</flux:button>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.undo()">Deshacer</flux:button>
                    </div>
                </div>
                
                <!-- Herramientas de color -->
                <div class="flex gap-2 items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium">Colores:</span>
                    <div class="flex gap-2">
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#FF6B6B')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500" style="background-color: #FF6B6B;" title="Rojo"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#4ECDC4')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500" style="background-color: #4ECDC4;" title="Verde"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#45B7D1')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500" style="background-color: #45B7D1;" title="Azul"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#96CEB4')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500" style="background-color: #96CEB4;" title="Verde Claro"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#FFEAA7')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500" style="background-color: #FFEAA7;" title="Amarillo"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#DDA0DD')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500" style="background-color: #DDA0DD;" title="Violeta"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#FFB347')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500" style="background-color: #FFB347;" title="Naranja"></button>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <span class="text-sm font-medium">Opacidad:</span>
                        <input type="range" min="0.1" max="1" step="0.1" value="0.5" onchange="if(window.pdfEditor) window.pdfEditor.setOpacity(this.value)" class="w-20">
                    </div>
                </div>

                <!-- Contenedor del PDF -->
                <div class="border rounded-lg bg-white p-4">
                    <div id="pdf-container" class="relative">
                        <canvas id="pdf-canvas" class="absolute top-0 left-0 z-10"></canvas>
                        <canvas id="fabric-canvas" class="absolute top-0 left-0 z-20"></canvas>
                    </div>
                    <div id="pdf-loading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-600">Cargando PDF...</p>
                    </div>
                </div>

                <!-- Controles de página -->
                <div class="flex justify-center items-center gap-4 mt-4">
                    <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.previousPage()" id="prev-page">« Anterior</flux:button>
                    <span id="page-info" class="text-sm">Página 1 de 1</span>
                    <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.nextPage()" id="next-page">Siguiente »</flux:button>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex gap-2 pt-4">
                <flux:button size="sm" variant="primary" wire:click='vender'>Vender Lote</flux:button>
                <flux:button size="sm" variant="ghost" onclick="Flux.modal('vender-lote').close()">Cancelar</flux:button>
            </div>
        </div>
    </flux:modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Definir la clase PDFEditor
            window.PDFEditor = class {
                constructor() {
                    console.log("contruyendo PDFEditor");
                    this.pdfDoc = null;
                    this.currentPage = 1;
                    this.scale = 1.5;
                    this.canvas = null;
                    this.ctx = null;
                    this.fabricCanvas = null;
                    this.currentColor = '#FF6B6B';
                    this.currentOpacity = 0.5;
                    this.modifications = [];
                    this.history = [];
                }

                async init(pdfUrl) {
                    console.log('🚀 Iniciando PDFEditor...');
                    console.log('📄 URL del PDF:', pdfUrl);
                    try {
                        this.canvas = document.getElementById('pdf-canvas');
                        this.ctx = this.canvas.getContext('2d');
                        
                        // Configurar PDF.js
                        if (typeof pdfjsLib !== 'undefined') {
                            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                            
                            // Cargar PDF
                            this.pdfDoc = await pdfjsLib.getDocument(pdfUrl).promise;
                            
                            // Renderizar primera página
                            await this.renderPage(1);
                            
                            // Inicializar Fabric.js
                            this.initFabricCanvas();
                            
                            document.getElementById('pdf-loading').style.display = 'none';
                            document.getElementById('pdf-container').style.display = 'block';
                            
                            this.updatePageInfo();
                        } else {
                            throw new Error('PDF.js no está cargado');
                        }
                    } catch (error) {
                        console.error('Error loading PDF:', error);
                        document.getElementById('pdf-loading').innerHTML = '<p class="text-red-600">Error al cargar el PDF: ' + error.message + '</p>';
                    }
                }

                async renderPage(pageNum) {
                    const page = await this.pdfDoc.getPage(pageNum);
                    const viewport = page.getViewport({ scale: this.scale });
                    
                    this.canvas.height = viewport.height;
                    this.canvas.width = viewport.width;
                    
                    const renderContext = {
                        canvasContext: this.ctx,
                        viewport: viewport
                    };
                    
                    await page.render(renderContext).promise;
                    
                    // Redimensionar canvas de Fabric.js
                    if (this.fabricCanvas) {
                        this.fabricCanvas.setDimensions({
                            width: viewport.width,
                            height: viewport.height
                        });
                    }
                }

                initFabricCanvas() {
                    if (typeof fabric === 'undefined') {
                        console.error('Fabric.js no está cargado');
                        return;
                    }

                    this.fabricCanvas = new fabric.Canvas('fabric-canvas', {
                        isDrawingMode: false,
                        selection: true,
                        preserveObjectStacking: true
                    });

                    // Configurar el canvas para que tenga las mismas dimensiones que el PDF
                    this.fabricCanvas.setDimensions({
                        width: this.canvas.width,
                        height: this.canvas.height
                    });

                    // Event listeners
                    this.fabricCanvas.on('mouse:down', (e) => {
                        if (e.target && e.target.type === 'rect') return;
                        
                        const pointer = this.fabricCanvas.getPointer(e.e);
                        this.startDrawing(pointer.x, pointer.y);
                    });

                    this.fabricCanvas.on('object:modified', () => {
                        this.saveState();
                    });
                }

                startDrawing(x, y) {
                    // Crear un rectángulo semitransparente para colorear
                    const rect = new fabric.Rect({
                        left: x - 25,
                        top: y - 25,
                        width: 50,
                        height: 50,
                        fill: this.currentColor,
                        opacity: this.currentOpacity,
                        stroke: this.currentColor,
                        strokeWidth: 2,
                        selectable: true,
                        moveable: true
                    });

                    this.fabricCanvas.add(rect);
                    this.fabricCanvas.setActiveObject(rect);
                    this.saveState();
                }

                setColor(color) {
                    this.currentColor = color;
                    const activeObject = this.fabricCanvas.getActiveObject();
                    if (activeObject) {
                        activeObject.set('fill', color);
                        activeObject.set('stroke', color);
                        this.fabricCanvas.renderAll();
                        this.saveState();
                    }
                }

                setOpacity(opacity) {
                    this.currentOpacity = parseFloat(opacity);
                    const activeObject = this.fabricCanvas.getActiveObject();
                    if (activeObject) {
                        activeObject.set('opacity', this.currentOpacity);
                        this.fabricCanvas.renderAll();
                        this.saveState();
                    }
                }

                clearAll() {
                    if (this.fabricCanvas) {
                        this.fabricCanvas.clear();
                        this.modifications = [];
                        this.history = [];
                    }
                }

                undo() {
                    if (this.history.length > 1) {
                        this.history.pop(); // Remove current state
                        const previousState = this.history[this.history.length - 1];
                        this.fabricCanvas.loadFromJSON(previousState, () => {
                            this.fabricCanvas.renderAll();
                        });
                    }
                }

                saveState() {
                    if (this.fabricCanvas) {
                        const canvasState = JSON.stringify(this.fabricCanvas.toJSON());
                        this.history.push(canvasState);
                        
                        // Limitar el historial a 20 estados
                        if (this.history.length > 20) {
                            this.history.shift();
                        }

                        // Guardar modificaciones para Livewire
                        this.modifications = this.fabricCanvas.getObjects().map(obj => ({
                            type: obj.type,
                            left: obj.left,
                            top: obj.top,
                            width: obj.width,
                            height: obj.height,
                            fill: obj.fill,
                            opacity: obj.opacity,
                            page: this.currentPage
                        }));

                        // Enviar a Livewire si está disponible
                        if (typeof Livewire !== 'undefined') {
                            Livewire.dispatch('savePdfModifications', [this.modifications]);
                        }
                    }
                }

                async nextPage() {
                    if (this.currentPage < this.pdfDoc.numPages) {
                        this.currentPage++;
                        await this.renderPage(this.currentPage);
                        this.updatePageInfo();
                    }
                }

                async previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        await this.renderPage(this.currentPage);
                        this.updatePageInfo();
                    }
                }

                updatePageInfo() {
                    const pageInfo = document.getElementById('page-info');
                    const prevBtn = document.getElementById('prev-page');
                    const nextBtn = document.getElementById('next-page');
                    
                    if (pageInfo) {
                        pageInfo.textContent = `Página ${this.currentPage} de ${this.pdfDoc.numPages}`;
                    }
                    if (prevBtn) {
                        prevBtn.disabled = this.currentPage === 1;
                    }
                    if (nextBtn) {
                        nextBtn.disabled = this.currentPage === this.pdfDoc.numPages;
                    }
                }
            };

            // Crear instancia global
            window.pdfEditor = new window.PDFEditor();
        });
    </script>

    @script
    <script>
        // Escuchar el evento de Livewire para inicializar el PDF
        $wire.on('VenderLote', (data) => {
            console.log('Evento VenderLote recibido:', data);
            setTimeout(() => {
                if (window.pdfEditor && $wire.pdfUrl) {
                    window.pdfEditor.init($wire.pdfUrl);
                }
            }, 1000);
        });

        // Agregar listener para modificaciones del PDF
        $wire.on('savePdfModifications', (modifications) => {
            $wire.call('savePdfModifications', modifications);
        });
    </script>
    @endscript
</div>