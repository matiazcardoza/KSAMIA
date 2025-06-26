<div>
    @foreach($menu_proyectos as $dato)
        <flux:menu.item :href="route('ventas', ['id_proyecto' => $dato->id_proyecto])" wire:navigate>{{ $dato->nom_proyecto }}</flux:menu.item>
    @endforeach
</div>
