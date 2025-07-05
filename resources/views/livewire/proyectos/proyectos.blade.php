<div>
    <flux:modal.trigger name="nuevo-proyecto">
        <flux:button>Nuevo Proyecto</flux:button>
    </flux:modal.trigger>

    <livewire:Proyectos.proyecto-nuevo>
    <livewire:Proyectos.proyecto-editar>
        
    <flux:modal name="eliminar-proyecto" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg"> Esta seguro de eliminar proyecto?</flux:heading>
    
                <flux:subheading>
                    <p>Estás a punto de eliminar este proyecto.</p>
                    <p>Esta acción no se puede revertir.</p>
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

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-rigth text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Nombre</th>
                    <th scope="col" class="px-6 py-3">Ubicación</th>
                    <th scope="col" class="px-6 py-3">Descripción</th>
                    <th scope="col" class="px-6 py-3">Presupuesto</th>
                    <th scope="col" class="px-6 py-3">Fecha</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proyectos as $proyecto)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td class="px-6 py-2 font-medium text-gray-900 dark:text-white">{{ $proyecto->id_proyecto }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $proyecto->nom_proyecto }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $proyecto->ubi_proyecto }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $proyecto->descripcion_proyecto }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $proyecto->presupuesto_proyecto ?? $proyecto->presuDolar_proyecto }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">{{ $proyecto->fecha_proyecto }}</td>
                        <td class="px-6 py-2 text-gray-600 dark:text-gray-300">
                            <flux:button variant="primary" size="sm" href="{{ route('proyectos.ver-lotes', ['id_proyecto' => $proyecto->id_proyecto]) }}" :current="request()->routeIs('proyectos.ver-lotes')" wire:navigate>Ver Lotes</flux:button>
                            <flux:button size="sm" wire:click='editar({{ $proyecto->id_proyecto }})'>Editar</flux:button>
                            <flux:button variant="danger" size="sm" wire:click='eliminar({{ $proyecto->id_proyecto }})'>Eliminar</flux:button>

                            <flux:button variant="ghost" size="sm" wire:click='eliminar({{ $proyecto->id_proyecto }})'>descargar evidencias</flux:button>
                        </td>
                    </tr>
                @endforeach
                
            </tbody>
        </table>
    </div>
</div>
