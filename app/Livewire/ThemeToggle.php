<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;

class ThemeToggle extends Component
{
    public function toggleTheme()
    {
        $currentTheme = Setting::get('theme', 'light');
        $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';
        Setting::set('theme', $newTheme);
        
        // Recargar la página para aplicar el nuevo tema
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.theme-toggle');
    }
}
