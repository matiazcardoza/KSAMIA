<?php

namespace App\Livewire;

use Livewire\Component;

class DarkModeToogle extends Component
{
    public $darkMode = false;

    public function mount()
    {
        $this->darkMode = session()->get('dark_mode', false);
    }

    public function toggleDarkMode()
    {
        $this->darkMode = !$this->darkMode;
        session()->put('dark_mode', $this->darkMode);
        $this->dispatch('darkModeToggled', $this->darkMode);
    }

    public function render()
    {
        return view('livewire.dark-mode-toogle');
    }
}
