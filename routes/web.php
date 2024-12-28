<?php

use App\Livewire\DaftarLaporan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    'verified',
    'role:admin'
])->group(function () {
    // Route::get('/daftar-laporan', DaftarVerifikasi::class)->name('daftar-verifikasi');

});

Route::middleware([
    'auth:sanctum',
    'verified',
    'role:daerah'
])->group(function () {
    Route::get('/daftar-laporan', DaftarLaporan::class)->name('daftar-laporan');
});
