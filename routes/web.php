<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login.index');
})->name('login');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('chat');


Route::post('/send', [App\Http\Controllers\DashboardController::class, 'send'])->name('send');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');