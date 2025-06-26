<div x-data="{ darkMode: @js(session('dark_mode', false)) }" 
     x-init="$watch('darkMode', value => {
         if (value) {
             document.documentElement.classList.add('dark');
         } else {
             document.documentElement.classList.remove('dark');
         }
     })">
    <button @click="darkMode = !darkMode; 
                    $wire.set('darkMode', darkMode); 
                    $wire.call('toggleDarkMode')" 
            class="btn btn-toggle">
        <span x-text="darkMode ? 'Modo Claro' : 'Modo Oscuro'"></span>
    </button>
</div>