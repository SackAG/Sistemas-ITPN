<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('inicio');
})->name('home');

// Route::get('dashboard', function () {
//     return view('index');
// })  ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::post('/api/settings/theme', function (Request $request) {
    $theme = $request->input('theme', 'light');
    if (in_array($theme, ['light', 'dark'])) {
        Setting::set('theme', $theme);
        return redirect()->back();
    }
    return redirect()->back()->withErrors(['theme' => 'Tema inválido']);
})->name('api.theme');

// Rutas de autenticación tradicional
Route::get('/login-simple', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login.simple');
Route::post('/login-simple', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::post('/logout-simple', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout.simple');

require __DIR__ . '/auth.php';
