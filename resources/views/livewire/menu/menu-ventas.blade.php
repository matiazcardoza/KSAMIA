<div>
    @foreach($menu_ventas as $dato)
        <flux:menu.item :href="route('ventas', ['id_tipo_venta' => $dato->id_tipo_venta])" wire:navigate>{{ __('por ') . $dato->nom_tipo_venta }}</flux:menu.item>
    @endforeach
</div>
