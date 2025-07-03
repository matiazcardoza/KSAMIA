<div>
    <!-- Cargar librerías necesarias ANTES del modal -->
    @once
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
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
            @if($pdfUrl)
            <div class="space-y-3 mt-4">
                <div class="flex justify-between items-center">
                    <flux:heading size="lg">Plano del Lote</flux:heading>
                    <div class="flex gap-2">
                        <flux:button size="sm" variant="ghost" onclick="window.reinitializePdf()">🔄 Reinicializar PDF</flux:button>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.clearAll()">Limpiar Todo</flux:button>
                        <flux:button size="sm" variant="ghost" onclick="if(window.pdfEditor) window.pdfEditor.undo()">Deshacer</flux:button>
                        <flux:button size="sm" variant="primary" onclick="window.testPdfLoad()">Test PDF</flux:button>
                        <flux:button size="sm" variant="ghost" onclick="window.testDirectPdfAccess()">Test Acceso Directo</flux:button>
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
            </div>
            @endif

            <!-- Botones de acción -->
            <div class="flex gap-2 pt-4">
                <flux:button size="sm" variant="primary" wire:click='vender'>Vender Lote</flux:button>
                <flux:button size="sm" variant="ghost" onclick="Flux.modal('vender-lote').close()">Cancelar</flux:button>
            </div>
        </div>
    </flux:modal>

    <script>
        // Función de test para debugging
        window.testPdfLoad = function() {
            // Loguea el valor recibido desde PHP
            const pdfUrl = '/storage/planosPdf/plano1.pdf';
            console.log('🧪 Testing PDF load...');
            console.log('PDF URL desde Blade:', pdfUrl);
            console.log('PDF.js loaded:', typeof pdfjsLib !== 'undefined');
            console.log('Fabric.js loaded:', typeof fabric !== 'undefined');
            
            if (pdfUrl) {
                fetch(pdfUrl)
                    .then(response => {
                        console.log('📄 PDF Response:', response.status, response.statusText);
                        return response.blob();
                    })
                    .then(blob => {
                        console.log('📄 PDF Blob size:', blob.size, 'bytes');
                    })
                    .catch(error => {
                        console.error('❌ PDF fetch error:', error);
                    });
            }
        };

        window.testDirectPdfAccess = function() {
        const pdfUrl = '/storage/planosPdf/plano1.pdf';
        console.log('🧪 Probando acceso directo al PDF...');
        
        // Prueba 1: Fetch simple
        fetch(pdfUrl)
            .then(response => {
                console.log('📄 Response status:', response.status);
                console.log('📄 Response headers:', response.headers);
                if (response.ok) {
                    console.log('✅ PDF accesible vía fetch');
                    return response.blob();
                } else {
                    throw new Error(`HTTP ${response.status}`);
                }
            })
            .then(blob => {
                console.log('📄 PDF blob size:', blob.size, 'bytes');
                console.log('📄 PDF blob type:', blob.type);
            })
            .catch(error => {
                console.error('❌ Error accessing PDF:', error);
            });
        
        // Prueba 2: Con PDF.js
        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.getDocument(pdfUrl).promise
                .then(pdf => {
                    console.log('✅ PDF.js puede cargar el PDF');
                    console.log('📄 Número de páginas:', pdf.numPages);
                })
                .catch(error => {
                    console.error('❌ PDF.js no puede cargar el PDF:', error);
                });
        } else {
            console.warn('⚠️ PDF.js no está cargado');
        }
    };

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 DOM loaded, checking libraries...');
            console.log('PDF.js:', typeof pdfjsLib !== 'undefined' ? '✅' : '❌');
            console.log('Fabric.js:', typeof fabric !== 'undefined' ? '✅' : '❌');
            
            // Definir la clase PDFEditor
            window.PDFEditor = class {
            constructor() {
                console.log("🏗️ Construyendo PDFEditor");
                this.pdfDoc = null;
                this.currentPage = 1;
                this.scale = 1.2; // Reducir escala inicial
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
                    
                    console.log('📥 Cargando documento PDF...');
                    
                    // Cargar PDF con timeout
                    const loadingTask = pdfjsLib.getDocument({
                        url: pdfUrl,
                        cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
                        cMapPacked: true
                    });
                    
                    this.pdfDoc = await loadingTask.promise;
                    console.log('✅ PDF cargado exitosamente. Páginas:', this.pdfDoc.numPages);
                    
                    // Renderizar primera página
                    await this.renderPage(1);
                    
                    // Inicializar Fabric.js DESPUÉS de renderizar el PDF
                    this.initFabricCanvas();
                    
                    // Mostrar el contenedor
                    document.getElementById('pdf-loading').style.display = 'none';
                    document.getElementById('pdf-error').style.display = 'none';
                    document.getElementById('pdf-container').style.display = 'block';
                    
                    this.updatePageInfo();
                    
                    console.log('🎉 PDFEditor inicializado exitosamente');
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
                console.log('🎨 Renderizando página', pageNum);
                
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
                console.log('✅ Página renderizada exitosamente');
                
                // Actualizar current page
                this.currentPage = pageNum;
                
                return { width: viewport.width, height: viewport.height };
            }

            initFabricCanvas() {
                console.log('🎨 Inicializando Fabric Canvas');
                
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
                    if (e.target && e.target.type === 'rect') return;
                    
                    const pointer = this.fabricCanvas.getPointer(e.e);
                    this.startDrawing(pointer.x, pointer.y);
                });

                this.fabricCanvas.on('object:modified', () => {
                    this.saveState();
                });
                
                console.log('✅ Fabric Canvas inicializado exitosamente');
            }

            // ... resto de métodos permanecen igual
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
                    this.history.pop();
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
                    
                    if (this.history.length > 20) {
                        this.history.shift();
                    }

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
        };

            // Crear instancia global
            window.pdfEditor = new window.PDFEditor();
        });
    </script>

    @script
    <script>
        let pdfInitialized = false;
        
        // Escuchar el evento de Livewire para inicializar el PDF
        $wire.on('VenderLote', (data) => {
            console.log('🎯 Evento VenderLote recibido:', data);
            pdfInitialized = false;
            
            // Resetear estado visual
            document.getElementById('pdf-loading').style.display = 'block';
            document.getElementById('pdf-container').style.display = 'none';
            document.getElementById('pdf-error').style.display = 'none';
            
            // Función para intentar inicializar el PDF
            function tryInitializePdf() {
                console.log('⏰ Intentando inicializar PDF...');
                console.log('PDF URL desde Livewire:', $wire.pdfUrl);
                console.log('window.pdfEditor existe:', !!window.pdfEditor);
                
                if (window.pdfEditor && $wire.pdfUrl && !pdfInitialized) {
                    console.log('🚀 Inicializando PDF Editor...');
                    pdfInitialized = true;
                    window.pdfEditor.init($wire.pdfUrl)
                        .then(() => {
                            console.log('✅ PDF inicializado exitosamente');
                        })
                        .catch(error => {
                            console.error('❌ Error al inicializar PDF:', error);
                            pdfInitialized = false;
                        });
                } else {
                    console.warn('⚠️ Esperando componentes...');
                    console.log('- PDF Editor:', !!window.pdfEditor);
                    console.log('- PDF URL:', !!$wire.pdfUrl);
                    console.log('- Ya inicializado:', pdfInitialized);
                    
                    // Reintentar después de un momento
                    if (!pdfInitialized) {
                        setTimeout(tryInitializePdf, 500);
                    }
                }
            }
            
            // Iniciar el proceso
            setTimeout(tryInitializePdf, 100);
        });

        // Agregar listener para modificaciones del PDF
        $wire.on('savePdfModifications', (modifications) => {
            $wire.call('savePdfModifications', modifications);
        });
        
        // Función para reinicializar manualmente (debugging)
        window.reinitializePdf = function() {
            pdfInitialized = false;
            if (window.pdfEditor && $wire.pdfUrl) {
                console.log('🔄 Reinicializando PDF manualmente...');
                window.pdfEditor.init($wire.pdfUrl);
            }
        };
    </script>
    @endscript
</div>