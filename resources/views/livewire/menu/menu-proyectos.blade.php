<div>
    @foreach($menu_proyectos as $dato)
        <li>
            <a href="{{ route('ventas', ['id_proyecto' => $dato->id_proyecto]) }}" 
               wire:navigate 
               class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                {{ $dato->nom_proyecto }}
            </a>
        </li>
    @endforeach
</div>
