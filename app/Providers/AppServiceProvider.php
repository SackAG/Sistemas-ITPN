<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\SesionClase;
use App\Models\UsoLibreEquipo;
use App\Models\ReservacionEquipo;
use App\Observers\SesionClaseObserver;
use App\Observers\UsoLibreEquipoObserver;
use App\Observers\ReservacionEquipoObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->singleton('settings', function () {
            return Cache::rememberForever('settings', function () {
                return Setting::all()->pluck('value', 'key')->toArray();
            });
        });

        // Registrar observers para auto-llenado de historial
        SesionClase::observe(SesionClaseObserver::class);
        UsoLibreEquipo::observe(UsoLibreEquipoObserver::class);
        ReservacionEquipo::observe(ReservacionEquipoObserver::class);
    }
}
