<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Auth::routes();
// Route::view('app', 'home');
Route::get('/', function () {
    return redirect('/OAGWXP');
});

Route::middleware(['guest'])->group(function () {;
    Route::prefix('OAGWXP')->group(function() {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
    });
});

Route::middleware(['auth', 'web'])->group(function() {
    Route::prefix('OAGWXP')->group(function() {
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/', function () {
            return view('home');
        });
    });
});
