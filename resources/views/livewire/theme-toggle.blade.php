<button wire:click="toggleTheme" class="theme-toggle" title="Cambiar tema">
    @if(app('settings')['theme'] === 'dark')
        <i class="bi bi-sun-fill"></i>
    @else
        <i class="bi bi-moon-stars-fill"></i>
    @endif
</button>
