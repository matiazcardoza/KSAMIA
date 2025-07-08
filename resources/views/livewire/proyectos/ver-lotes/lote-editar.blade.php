
<div>
    <!-- Cargar librerías necesarias ANTES del modal -->
    @once
    <script>
        // Cargar PDF.js solo si no está cargado
        if (typeof pdfjsLib === 'undefined') {
            const pdfScript = document.createElement('script');
            pdfScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
            document.head.appendChild(pdfScript);
        }
        
        // Cargar Fabric.js solo si no está cargado
        if (typeof fabric === 'undefined') {
            const fabricScript = document.createElement('script');
            fabricScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js';
            document.head.appendChild(fabricScript);
        }
    </script>
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
        .history-info {
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            color: #6b7280;
        }

        .pan-mode {
            cursor: grabbing !important;
        }
        .pan-mode * {
            cursor: grabbing !important;
        }
        .pan-active {
            background-color: #3b82f6 !important;
            color: white !important;
        }
    </style>
    @endonce

    <flux:modal name="editar-lote" class="max-w-7xl w-full">
        <div class="space-y-5">
            <div class="space-y-4">
                <flux:heading size="lg">Editar Lote con id - {{ $id_lote }}</flux:heading>
                <flux:subheading>Ver detalles de lote, editar un lote</flux:subheading>
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
            @if($pdfUrl)
            <div class="space-y-3 mt-4">
                <div class="flex justify-between items-center">
                    <flux:heading size="lg">Plano del Lote</flux:heading>
                    <div class="flex gap-2 items-center">
                        <div class="history-info">
                            <span id="history-count">Historial: 0</span>
                        </div>
                        <flux:button size="sm" variant="ghost" onclick="window.reinitializePdf()" title="Reinicializar PDF">🔄</flux:button>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.clearAll()" title="Limpiar Todo">🗑️</flux:button>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.undo()" title="Deshacer (Ctrl+Z)">↶</flux:button>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.deleteSelected()" title="Eliminar Seleccionado (Delete)">🗑️</flux:button>
                    </div>
                </div>
                
                <!-- Herramientas de color -->
                <div class="flex gap-2 items-center p-3 bg-gray-50 rounded-lg flex-wrap">
                    <span class="text-sm font-medium">Colores:</span>
                    <div class="flex gap-2">
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#FF6B6B')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500 transition-colors" style="background-color: #FF6B6B;" title="Rojo"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#4ECDC4')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500 transition-colors" style="background-color: #4ECDC4;" title="Verde"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#45B7D1')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500 transition-colors" style="background-color: #45B7D1;" title="Azul"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#96CEB4')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500 transition-colors" style="background-color: #96CEB4;" title="Verde Claro"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#FFEAA7')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500 transition-colors" style="background-color: #FFEAA7;" title="Amarillo"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#DDA0DD')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500 transition-colors" style="background-color: #DDA0DD;" title="Violeta"></button>
                        <button onclick="if(window.pdfEditor) window.pdfEditor.setColor('#FFB347')" class="w-8 h-8 rounded border-2 border-gray-300 hover:border-gray-500 transition-colors" style="background-color: #FFB347;" title="Naranja"></button>
                    </div>
                    <div class="flex gap-2 ml-4 items-center">
                        <span class="text-sm font-medium">Opacidad:</span>
                        <input type="range" min="0.1" max="1" step="0.1" value="0.5" onchange="if(window.pdfEditor) window.pdfEditor.setOpacity(this.value)" class="w-20">
                        <span id="opacity-value" class="text-sm text-gray-600">50%</span>
                    </div>
                    <div class="flex gap-2 ml-4 items-center">
                        <span class="text-sm font-medium">Zoom:</span>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.zoomOut()" title="Zoom Out">🔍-</flux:button>
                        <span id="zoom-value" class="text-sm text-gray-600 min-w-[60px] text-center">120%</span>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.zoomIn()" title="Zoom In">🔍+</flux:button>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.resetZoom()" title="Reset Zoom">🔄</flux:button>
                    </div>
                    <div class="flex gap-2 ml-4 items-center">
                        <span class="text-sm font-medium">Mover:</span>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.togglePanMode()" id="pan-toggle" title="Activar/Desactivar modo mover (Espacio)">✋</flux:button>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.centerPDF()" title="Centrar PDF">🎯</flux:button>
                        <span id="pan-status" class="text-xs text-gray-500">Modo: Editar</span>
                    </div>
                </div>

                <!-- Contenedor del PDF -->
                <div class="border rounded-lg bg-white p-4">
                    <div id="pdf-container" class="relative overflow-auto" style="max-height: 600px; cursor: grab;">
                        <div id="pdf-wrapper" class="relative inline-block" style="transform-origin: top left;">
                            <canvas id="pdf-canvas" class="absolute top-0 left-0 z-10"></canvas>
                            <canvas id="fabric-canvas" class="absolute top-0 left-0 z-20"></canvas>
                        </div>
                    </div>
                    <div id="pdf-loading" class="text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-600">Cargando PDF...</p>
                    </div>
                    <div id="pdf-error" class="text-center py-8 hidden">
                        <p class="text-red-600">Error al cargar el PDF</p>
                    </div>
                </div>

                <!-- Controles de página -->
                <div class="flex justify-center items-center gap-4 mt-4">
                    <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.previousPage()" id="prev-page">« Anterior</flux:button>
                    <span id="page-info" class="text-sm">Página 1 de 1</span>
                    <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.nextPage()" id="next-page">Siguiente »</flux:button>
                </div>

                <!-- Información adicional -->
                <div class="text-xs text-gray-500 text-center">
                    <p>💡 Consejos: Ctrl+Z (deshacer), Delete (eliminar), Ctrl+/- (zoom), Ctrl+0 (reset zoom), Espacio (mover), Flechas (desplazar)</p>
                </div>
            </div>
            @endif

            <!-- Botones de acción -->
            <div class="flex gap-2 pt-4">
                <flux:button size="sm" variant="primary" wire:click='update'>Actualizar Lote</flux:button>
                <flux:button size="sm" variant="ghost" onclick="Flux.modal('editar-lote').close()">Cancelar</flux:button>
            </div>
        </div>
    </flux:modal>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.PDFEditor = class {
                constructor() {
                    this.minZoom = 0.5;
                    this.maxZoom = 3.0;
                    this.zoomStep = 0.2;
                    this.isPanMode = false;
                    this.isPanning = false;
                    this.lastPanPoint = { x: 0, y: 0 };
                    this.panOffset = { x: 0, y: 0 };
                    this.pdfContainer = null;
                    this.pdfWrapper = null;
                    this.pdfDoc = null;
                    this.currentPage = 1;
                    this.scale = 1.2;
                    this.canvas = null;
                    this.ctx = null;
                    this.fabricCanvas = null;
                    this.currentColor = '#FF6B6B';
                    this.currentOpacity = 0.5;
                    this.modifications = [];
                    this.history = [];
                    this.isInitialized = false;
                }

                async init(pdfUrl) {
                    try {
                        // Limpiar estado previo
                        if (this.fabricCanvas) {
                            this.fabricCanvas.dispose();
                            this.fabricCanvas = null;
                        }
                        
                        // Verificar que las librerías estén cargadas
                        if (typeof pdfjsLib === 'undefined') {
                            throw new Error('PDF.js no está cargado');
                        }
                        
                        if (typeof fabric === 'undefined') {
                            throw new Error('Fabric.js no está cargado');
                        }

                        // Obtener elementos del DOM
                        this.canvas = document.getElementById('pdf-canvas');
                        const fabricCanvasElement = document.getElementById('fabric-canvas');
                        
                        if (!this.canvas) {
                            throw new Error('Canvas #pdf-canvas no encontrado');
                        }
                        
                        if (!fabricCanvasElement) {
                            throw new Error('Canvas #fabric-canvas no encontrado');
                        }
                        
                        this.ctx = this.canvas.getContext('2d');
                        
                        // Configurar PDF.js worker
                        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                        
                        // Cargar PDF
                        const loadingTask = pdfjsLib.getDocument({
                            url: pdfUrl,
                            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
                            cMapPacked: true
                        });
                        
                        this.pdfDoc = await loadingTask.promise;                        
                        // Renderizar primera página
                        await this.renderPage(1);
                        
                        // Inicializar Fabric.js DESPUÉS de renderizar el PDF
                        this.initFabricCanvas();
                        
                        // Configurar atajos de teclado
                        this.addKeyboardShortcuts();
                        
                        // Mostrar el contenedor
                        document.getElementById('pdf-loading').style.display = 'none';
                        document.getElementById('pdf-error').style.display = 'none';
                        document.getElementById('pdf-container').style.display = 'block';
                        
                        this.updatePageInfo();
                        this.updateZoomInfo();
                        this.initPanControls();
                        this.centerPDF();
                        this.updateHistoryInfo();
                        this.isInitialized = true;
                        return true;
                        
                    } catch (error) {
                        console.error('❌ Error loading PDF:', error);
                        document.getElementById('pdf-loading').style.display = 'none';
                        document.getElementById('pdf-container').style.display = 'none';
                        
                        const errorElement = document.getElementById('pdf-error');
                        errorElement.style.display = 'block';
                        errorElement.innerHTML = `<p class="text-red-600">Error al cargar el PDF: ${error.message}</p>`;
                        
                        throw error;
                    }
                }

                async renderPage(pageNum) {        
                    if (!this.pdfDoc) {
                        throw new Error('PDF no cargado');
                    }
                    
                    const page = await this.pdfDoc.getPage(pageNum);
                    const viewport = page.getViewport({ scale: this.scale });
                    
                    // Configurar canvas
                    this.canvas.height = viewport.height;
                    this.canvas.width = viewport.width;
                    
                    // Limpiar canvas
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    
                    const renderContext = {
                        canvasContext: this.ctx,
                        viewport: viewport
                    };
                    
                    await page.render(renderContext).promise;                    
                    // Actualizar current page
                    this.currentPage = pageNum;
                    
                    return { width: viewport.width, height: viewport.height };
                }

                initFabricCanvas() {                    
                    // Limpiar canvas previo si existe
                    if (this.fabricCanvas) {
                        this.fabricCanvas.dispose();
                    }
                    
                    this.fabricCanvas = new fabric.Canvas('fabric-canvas', {
                        isDrawingMode: false,
                        selection: true,
                        preserveObjectStacking: true
                    });

                    // Configurar dimensiones
                    this.fabricCanvas.setDimensions({
                        width: this.canvas.width,
                        height: this.canvas.height
                    });

                    // Event listeners
                    this.fabricCanvas.on('mouse:down', (e) => {
                        // Solo crear objetos si NO estamos en modo pan y NO hay un objeto seleccionado
                        if (this.isPanMode) {
                            return; // No hacer nada en modo pan
                        }
                        
                        if (e.target && e.target.type === 'rect') {
                            return; // No crear si ya hay un objeto seleccionado
                        }
                        
                        const pointer = this.fabricCanvas.getPointer(e.e);
                        this.startDrawing(pointer.x, pointer.y);
                    });

                    this.fabricCanvas.on('object:modified', () => {
                        this.saveState();
                    });

                    this.fabricCanvas.on('selection:created', () => {
                        this.updateSelectedObjectProperties();
                    });

                    this.fabricCanvas.on('selection:updated', () => {
                        this.updateSelectedObjectProperties();
                    });

                    // Limpiar historial y guardar estado inicial vacío
                    this.history = [];
                    this.saveState();
                }

                startDrawing(x, y) {
                    
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
                    
                    // Actualizar visualmente el color seleccionado
                    this.updateColorPickers(color);
                }

                setOpacity(opacity) {
                    this.currentOpacity = parseFloat(opacity);
                    const activeObject = this.fabricCanvas.getActiveObject();
                    if (activeObject) {
                        activeObject.set('opacity', this.currentOpacity);
                        this.fabricCanvas.renderAll();
                        this.saveState();
                    }
                    
                    // Actualizar el valor mostrado
                    const opacityValue = document.getElementById('opacity-value');
                    if (opacityValue) {
                        opacityValue.textContent = Math.round(opacity * 100) + '%';
                    }
                }

                clearAll() {
                    if (this.fabricCanvas) {
                        this.fabricCanvas.clear();
                        this.modifications = [];
                        this.history = [];
                        this.saveState();
                    }
                }

                undo() {
                    if (this.history.length > 1) {
                        // Remover el estado actual
                        this.history.pop();
                        
                        // Obtener el estado anterior
                        const previousState = this.history[this.history.length - 1];
                        
                        if (previousState) {
                            // Cargar el estado anterior de forma asíncrona
                            this.fabricCanvas.loadFromJSON(previousState, () => {
                                this.fabricCanvas.renderAll();
                                this.updateHistoryInfo();
                            });
                        } else {
                            // Si no hay estado anterior, limpiar el canvas
                            this.fabricCanvas.clear();
                            this.updateHistoryInfo();
                        }
                    }
                }

                deleteSelected() {
                    const activeObject = this.fabricCanvas.getActiveObject();
                    if (activeObject) {
                        this.fabricCanvas.remove(activeObject);
                        this.saveState();
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

                        // Actualizar modificaciones
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

                        // Enviar modificaciones a Livewire
                        if (typeof Livewire !== 'undefined') {
                            Livewire.dispatch('savePdfModifications', [this.modifications]);
                        }
                        
                        this.updateHistoryInfo();
                    }
                }

                updateSelectedObjectProperties() {
                    const activeObject = this.fabricCanvas.getActiveObject();
                    if (activeObject) {
                        // Actualizar los controles con las propiedades del objeto seleccionado
                        this.currentColor = activeObject.fill;
                        this.currentOpacity = activeObject.opacity;
                        
                        // Actualizar UI
                        this.updateColorPickers(this.currentColor);
                        const opacitySlider = document.querySelector('input[type="range"]');
                        if (opacitySlider) {
                            opacitySlider.value = this.currentOpacity;
                        }
                        this.setOpacity(this.currentOpacity);
                    }
                }

                updateColorPickers(selectedColor) {
                    // Remover clase activa de todos los botones
                    document.querySelectorAll('.color-picker-active').forEach(btn => {
                        btn.classList.remove('color-picker-active');
                    });
                    
                    // Agregar clase activa al color seleccionado
                    const colorButtons = document.querySelectorAll('button[onclick*="setColor"]');
                    colorButtons.forEach(btn => {
                        if (btn.style.backgroundColor === selectedColor) {
                            btn.classList.add('color-picker-active');
                        }
                    });
                }

                updateHistoryInfo() {
                    const historyCount = document.getElementById('history-count');
                    if (historyCount) {
                        historyCount.textContent = `Historial: ${this.history.length}`;
                    }
                }

                addKeyboardShortcuts() {
                    // Remover listener previo si existe
                    if (this.keyboardHandler) {
                        document.removeEventListener('keydown', this.keyboardHandler);
                    }
                    
                    this.keyboardHandler = (e) => {
                        // Solo procesar si el modal está abierto y el editor está inicializado
                        if (!this.isInitialized) return;
                        
                        // Ctrl+Z para deshacer
                        if (e.ctrlKey && e.key === 'z') {
                            e.preventDefault();
                            this.undo();
                            return;
                        }
                        
                        // Delete o Backspace para eliminar objeto seleccionado
                        if (e.key === 'Delete' || e.key === 'Backspace') {
                            // Solo si no estamos en un input
                            if (!e.target.matches('input, textarea')) {
                                e.preventDefault();
                                this.deleteSelected();
                            }
                            return;
                        }
                        
                        // Escape para deseleccionar
                        if (e.key === 'Escape') {
                            this.fabricCanvas.discardActiveObject();
                            this.fabricCanvas.renderAll();
                            return;
                        }

                        // Ctrl + Plus para zoom in
                        if (e.ctrlKey && (e.key === '+' || e.key === '=')) {
                            e.preventDefault();
                            this.zoomIn();
                            return;
                        }

                        // Ctrl + Minus para zoom out
                        if (e.ctrlKey && e.key === '-') {
                            e.preventDefault();
                            this.zoomOut();
                            return;
                        }

                        // Ctrl + 0 para reset zoom
                        if (e.ctrlKey && e.key === '0') {
                            e.preventDefault();
                            this.resetZoom();
                            return;
                        }

                        // Barra espaciadora para toggle pan mode
                        if (e.code === 'Space' && !e.target.matches('input, textarea')) {
                            e.preventDefault();
                            this.togglePanMode();
                            return;
                        }

                        // Teclas de flecha para mover el PDF
                        if (this.isPanMode && ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                            e.preventDefault();
                            const moveAmount = e.shiftKey ? 50 : 20;
                            
                            switch(e.key) {
                                case 'ArrowUp':
                                    this.panOffset.y += moveAmount;
                                    break;
                                case 'ArrowDown':
                                    this.panOffset.y -= moveAmount;
                                    break;
                                case 'ArrowLeft':
                                    this.panOffset.x += moveAmount;
                                    break;
                                case 'ArrowRight':
                                    this.panOffset.x -= moveAmount;
                                    break;
                            }
                            
                            this.applyPanTransform();
                            return;
                        }

                        // Ctrl + Home para centrar
                        if (e.ctrlKey && e.key === 'Home') {
                            e.preventDefault();
                            this.centerPDF();
                            return;
                        }
                    };
                    
                    document.addEventListener('keydown', this.keyboardHandler);
                }

                async nextPage() {
                    if (this.currentPage < this.pdfDoc.numPages) {
                        this.currentPage++;
                        await this.renderPage(this.currentPage);
                        this.updatePageInfo();
                        
                        // Redimensionar fabric canvas
                        this.fabricCanvas.setDimensions({
                            width: this.canvas.width,
                            height: this.canvas.height
                        });
                    }
                }

                async previousPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        await this.renderPage(this.currentPage);
                        this.updatePageInfo();
                        
                        // Redimensionar fabric canvas
                        this.fabricCanvas.setDimensions({
                            width: this.canvas.width,
                            height: this.canvas.height
                        });
                    }
                }

                updatePageInfo() {
                    const pageInfo = document.getElementById('page-info');
                    const prevBtn = document.getElementById('prev-page');
                    const nextBtn = document.getElementById('next-page');
                    
                    if (pageInfo && this.pdfDoc) {
                        pageInfo.textContent = `Página ${this.currentPage} de ${this.pdfDoc.numPages}`;
                    }
                    if (prevBtn) {
                        prevBtn.disabled = this.currentPage === 1;
                    }
                    if (nextBtn) {
                        nextBtn.disabled = this.pdfDoc ? this.currentPage === this.pdfDoc.numPages : true;
                    }
                }

                async zoomIn() {
                    if (this.scale < this.maxZoom) {
                        this.scale = Math.min(this.scale + this.zoomStep, this.maxZoom);
                        await this.renderCurrentPage();
                        this.updateZoomInfo();
                    }
                }

                async zoomOut() {
                    if (this.scale > this.minZoom) {
                        this.scale = Math.max(this.scale - this.zoomStep, this.minZoom);
                        await this.renderCurrentPage();
                        this.updateZoomInfo();
                    }
                }

                async resetZoom() {
                    this.scale = 1.2; // Valor por defecto
                    await this.renderCurrentPage();
                    this.updateZoomInfo();
                }

                async renderCurrentPage() {
                    if (this.pdfDoc && this.currentPage) {
                        const dimensions = await this.renderPage(this.currentPage);
                        
                        // Redimensionar fabric canvas para que coincida con el nuevo tamaño
                        if (this.fabricCanvas) {
                            this.fabricCanvas.setDimensions({
                                width: dimensions.width,
                                height: dimensions.height
                            });
                            this.fabricCanvas.renderAll();
                        }
                        
                        // Aplicar transformación de pan
                        this.applyPanTransform();
                    }
                }

                updateZoomInfo() {
                    const zoomValue = document.getElementById('zoom-value');
                    if (zoomValue) {
                        zoomValue.textContent = Math.round(this.scale * 100) + '%';
                    }
                }

                initPanControls() {                    
                    this.pdfContainer = document.getElementById('pdf-container');
                    this.pdfWrapper = document.getElementById('pdf-wrapper');
                    
                    if (!this.pdfContainer || !this.pdfWrapper) {
                        console.error('❌ No se encontraron elementos para pan');
                        return;
                    }
                    
                    // Event listeners para el movimiento
                    this.pdfContainer.addEventListener('mousedown', (e) => this.handlePanStart(e));
                    this.pdfContainer.addEventListener('mousemove', (e) => this.handlePanMove(e));
                    this.pdfContainer.addEventListener('mouseup', (e) => this.handlePanEnd(e));
                    this.pdfContainer.addEventListener('mouseleave', (e) => this.handlePanEnd(e));
                    
                    // Prevenir selección de texto durante el pan
                    this.pdfContainer.addEventListener('selectstart', (e) => {
                        if (this.isPanMode || this.isPanning) {
                            e.preventDefault();
                        }
                    });
                }

                togglePanMode() {
                    this.isPanMode = !this.isPanMode;
                    
                    const panButton = document.getElementById('pan-toggle');
                    const panStatus = document.getElementById('pan-status');
                    
                    if (this.isPanMode) {
                        // Activar modo pan
                        this.pdfContainer.style.cursor = 'grab';
                        if (panButton) panButton.classList.add('pan-active');
                        if (panStatus) panStatus.textContent = 'Modo: Mover';
                        
                        // Desactivar completamente la interacción con Fabric
                        if (this.fabricCanvas) {
                            this.fabricCanvas.selection = false;
                            this.fabricCanvas.defaultCursor = 'grab';
                            this.fabricCanvas.hoverCursor = 'grab';
                            this.fabricCanvas.moveCursor = 'grab';
                            
                            // Hacer todos los objetos no seleccionables y no interactivos
                            this.fabricCanvas.getObjects().forEach(obj => {
                                obj.selectable = false;
                                obj.evented = false;
                                obj.moveable = false;
                                obj.hoverCursor = 'grab';
                            });
                            
                            // Desactivar eventos de mouse en Fabric
                            this.fabricCanvas.off('mouse:down');
                            this.fabricCanvas.off('mouse:move');
                            this.fabricCanvas.off('mouse:up');
                        }
                    } else {
                        // Desactivar modo pan
                        this.pdfContainer.style.cursor = 'default';
                        if (panButton) panButton.classList.remove('pan-active');
                        if (panStatus) panStatus.textContent = 'Modo: Editar';
                        
                        // Reactivar interacción con Fabric
                        if (this.fabricCanvas) {
                            this.fabricCanvas.selection = true;
                            this.fabricCanvas.defaultCursor = 'default';
                            this.fabricCanvas.hoverCursor = 'move';
                            this.fabricCanvas.moveCursor = 'move';
                            
                            // Hacer todos los objetos seleccionables e interactivos
                            this.fabricCanvas.getObjects().forEach(obj => {
                                obj.selectable = true;
                                obj.evented = true;
                                obj.moveable = true;
                                obj.hoverCursor = 'move';
                            });
                            
                            // Reactivar eventos de mouse en Fabric
                            this.fabricCanvas.on('mouse:down', (e) => {
                                if (this.isPanMode) return;
                                if (e.target && e.target.type === 'rect') return;
                                
                                const pointer = this.fabricCanvas.getPointer(e.e);
                                this.startDrawing(pointer.x, pointer.y);
                            });
                        }
                    }
                    
                    this.fabricCanvas?.renderAll();
                }

                handlePanStart(e) {
                    if (!this.isPanMode) return;
                    
                    e.preventDefault();
                    e.stopPropagation(); // Evitar que el evento se propague a Fabric
                    
                    this.isPanning = true;
                    this.lastPanPoint = { x: e.clientX, y: e.clientY };
                    this.pdfContainer.style.cursor = 'grabbing';
                }

                handlePanMove(e) {
                    if (!this.isPanMode || !this.isPanning) return;
                    
                    e.preventDefault();
                    e.stopPropagation(); // Evitar que el evento se propague a Fabric
                    
                    const deltaX = e.clientX - this.lastPanPoint.x;
                    const deltaY = e.clientY - this.lastPanPoint.y;
                    
                    this.panOffset.x += deltaX;
                    this.panOffset.y += deltaY;
                    
                    this.applyPanTransform();
                    
                    this.lastPanPoint = { x: e.clientX, y: e.clientY };
                }

                handlePanEnd(e) {
                    if (!this.isPanning) return;
                    
                    e.preventDefault();
                    e.stopPropagation(); // Evitar que el evento se propague a Fabric
                    
                    this.isPanning = false;
                    this.pdfContainer.style.cursor = this.isPanMode ? 'grab' : 'default';
                }

                applyPanTransform() {
                    if (this.pdfWrapper) {
                        const transform = `translate(${this.panOffset.x}px, ${this.panOffset.y}px) scale(${this.scale})`;
                        this.pdfWrapper.style.transform = transform;
                    }
                }

                centerPDF() {
                    if (!this.pdfContainer || !this.pdfWrapper) return;
                    
                    const containerRect = this.pdfContainer.getBoundingClientRect();
                    const wrapperRect = this.pdfWrapper.getBoundingClientRect();
                    
                    // Calcular offset para centrar
                    const centerX = (containerRect.width - (this.canvas.width * this.scale)) / 2;
                    const centerY = (containerRect.height - (this.canvas.height * this.scale)) / 2;
                    
                    this.panOffset.x = Math.max(0, centerX);
                    this.panOffset.y = Math.max(0, centerY);
                    
                    this.applyPanTransform();
                }

                resetPanAndZoom() {
                    this.panOffset = { x: 0, y: 0 };
                    this.scale = 1.2;
                    this.renderCurrentPage().then(() => {
                        this.centerPDF();
                        this.updateZoomInfo();
                    });
                }

                reinitializeFabricEvents() {
                    if (!this.fabricCanvas) return;
                    
                    // Limpiar eventos existentes
                    this.fabricCanvas.off('mouse:down');
                    this.fabricCanvas.off('object:modified');
                    this.fabricCanvas.off('selection:created');
                    this.fabricCanvas.off('selection:updated');
                    
                    // Volver a agregar eventos
                    this.fabricCanvas.on('mouse:down', (e) => {
                        if (this.isPanMode) return;
                        if (e.target && e.target.type === 'rect') return;
                        
                        const pointer = this.fabricCanvas.getPointer(e.e);
                        this.startDrawing(pointer.x, pointer.y);
                    });

                    this.fabricCanvas.on('object:modified', () => {
                        this.saveState();
                    });

                    this.fabricCanvas.on('selection:created', () => {
                        this.updateSelectedObjectProperties();
                    });

                    this.fabricCanvas.on('selection:updated', () => {
                        this.updateSelectedObjectProperties();
                    });
                }

                destroy() {
                    if (this.fabricCanvas) {
                        this.fabricCanvas.dispose();
                        this.fabricCanvas = null;
                    }
                    
                    if (this.keyboardHandler) {
                        document.removeEventListener('keydown', this.keyboardHandler);
                    }

                    if (this.pdfContainer) {
                        this.pdfContainer.removeEventListener('mousedown', this.handlePanStart);
                        this.pdfContainer.removeEventListener('mousemove', this.handlePanMove);
                        this.pdfContainer.removeEventListener('mouseup', this.handlePanEnd);
                        this.pdfContainer.removeEventListener('mouseleave', this.handlePanEnd);
                    }
                    
                    this.isInitialized = false;
                }
            };

            // Crear instancia global
            window.pdfEditor = new window.PDFEditor();
        });
    </script>

    @script
    <script>
        let pdfInitialized = false;
        
        // Escuchar el evento de Livewire para inicializar el PDF
        $wire.on('InitializePDF', (data) => {
            pdfInitialized = false;
            
            // Función para verificar si los elementos del DOM están disponibles
            function waitForDOMElements() {
                const pdfLoading = document.getElementById('pdf-loading');
                const pdfContainer = document.getElementById('pdf-container');
                const pdfError = document.getElementById('pdf-error');
                
                if (!pdfLoading || !pdfContainer || !pdfError) {
                    setTimeout(waitForDOMElements, 100);
                    return;
                }
                
                // Resetear estado visual
                pdfLoading.style.display = 'block';
                pdfContainer.style.display = 'none';
                pdfError.style.display = 'none';
                
                // Función para intentar inicializar el PDF
                function tryInitializePdf() {
                    
                    if (window.pdfEditor && $wire.pdfUrl && !pdfInitialized) {
                        pdfInitialized = true;
                        window.pdfEditor.init($wire.pdfUrl)
                    } else {        
                        // Reintentar después de un momento
                        if (!pdfInitialized) {
                            setTimeout(tryInitializePdf, 300);
                        }
                    }
                }
                
                // Iniciar el proceso de inicialización del PDF
                setTimeout(tryInitializePdf, 200);
            }
            
            // Iniciar verificación de elementos del DOM
            setTimeout(waitForDOMElements, 100);
        });

        // Agregar listener para modificaciones del PDF
        $wire.on('savePdfModifications', (modifications) => {
            $wire.call('savePdfModifications', modifications);
        });
        
        // Función para reinicializar manualmente (debugging)
        window.reinitializePdf = function() {
            pdfInitialized = false;
            if (window.pdfEditor && $wire.pdfUrl) {
                window.pdfEditor.init($wire.pdfUrl);
            }
        };
    </script>
    @endscript
    @script
    <script>
        // Agregar este listener después del script existente
        $wire.on('create-modified-pdf', async (data) => {
            try {
                console.log('🔄 Generando PDF modificado...');
                
                const { originalPdfUrl, modifications, projectPath } = data;
                
                if (!window.pdfEditor || !window.pdfEditor.fabricCanvas) {
                    console.error('❌ Editor PDF no disponible');
                    return;
                }
                
                // Crear un nuevo canvas temporal para combinar PDF + modificaciones
                const tempCanvas = document.createElement('canvas');
                const tempCtx = tempCanvas.getContext('2d');
                
                // Obtener dimensiones del PDF actual
                const pdfCanvas = document.getElementById('pdf-canvas');
                const fabricCanvas = window.pdfEditor.fabricCanvas;
                
                if (!pdfCanvas || !fabricCanvas) {
                    console.error('❌ Canvas no disponibles');
                    return;
                }
                
                // Configurar canvas temporal con las mismas dimensiones
                tempCanvas.width = pdfCanvas.width;
                tempCanvas.height = pdfCanvas.height;
                
                // Dibujar el PDF original
                tempCtx.drawImage(pdfCanvas, 0, 0);
                
                // Dibujar las modificaciones de Fabric.js encima
                const fabricCanvasElement = fabricCanvas.getElement();
                tempCtx.drawImage(fabricCanvasElement, 0, 0);
                
                // Convertir a PDF usando jsPDF
                const { jsPDF } = window.jspdf || {};
                
                if (!jsPDF) {
                    // Si jsPDF no está disponible, usar canvas como imagen
                    const dataURL = tempCanvas.toDataURL('image/jpeg', 0.95);
                    
                    // Enviar al servidor para conversión a PDF
                    $wire.call('saveModifiedPdf', dataURL, projectPath);
                    console.log('✅ PDF modificado enviado al servidor');
                    
                } else {
                    // Usar jsPDF si está disponible
                    const imgData = tempCanvas.toDataURL('image/jpeg', 0.95);
                    const pdf = new jsPDF({
                        orientation: tempCanvas.width > tempCanvas.height ? 'landscape' : 'portrait',
                        unit: 'px',
                        format: [tempCanvas.width, tempCanvas.height]
                    });
                    
                    pdf.addImage(imgData, 'JPEG', 0, 0, tempCanvas.width, tempCanvas.height);
                    const pdfData = pdf.output('datauristring');
                    
                    $wire.call('saveModifiedPdf', pdfData, projectPath);
                    console.log('✅ PDF modificado generado y enviado');
                }
                
                // Limpiar canvas temporal
                tempCanvas.remove();
                
            } catch (error) {
                console.error('❌ Error al generar PDF modificado:', error);
            }
        });
    </script>
    @endscript
</div>