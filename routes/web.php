<?php

use App\Http\Controllers\PasskeyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ServiceController::class, 'index'])->name('dashboard');
    Route::resource('services', ServiceController::class)->except(['index', 'show']);

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/settings/2fa/setup', [SettingsController::class, 'twoFactorSetup'])->name('settings.2fa.setup');
    Route::post('/settings/2fa/enable', [SettingsController::class, 'twoFactorEnable'])->name('settings.2fa.enable');
    Route::post('/settings/2fa/disable', [SettingsController::class, 'twoFactorDisable'])->name('settings.2fa.disable');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/sessions/{session}', [SettingsController::class, 'destroySession'])->name('settings.sessions.destroy');
    Route::post('/settings/sessions/destroy-all', [SettingsController::class, 'destroyAllSessions'])->name('settings.sessions.destroy-all');

    Route::post('/passkeys/options', [PasskeyController::class, 'options'])->name('passkeys.options');
    Route::post('/passkeys', [PasskeyController::class, 'store'])->name('passkeys.store');
    Route::delete('/passkeys/{passkey}', [PasskeyController::class, 'destroy'])->name('passkeys.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
