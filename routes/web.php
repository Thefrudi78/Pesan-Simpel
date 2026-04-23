<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/login', function () {
    return view('login.index');
})->name('login');

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('chat');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');