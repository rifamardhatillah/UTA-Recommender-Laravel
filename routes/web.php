<?php

use Illuminate\Support\Facades\Route;

//import kriteria controller
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtaController;

Route::get('/uta-selection', [UtaController::class, 'index'])->name('uta.index');
Route::post('/uta-selection/calculate', [UtaController::class, 'calculate'])->name('uta.calculate');
// TAMBAHKAN ROUTE BARU DI SINI
Route::get('/uta-selection/download-pdf', [UtaController::class, 'downloadPdf'])->name('uta.downloadPdf');

//route resource for kriterias
Route::resource('/kriterias', KriteriaController::class);
Route::resource('/alternatifs', AlternatifController::class);
Route::resource('/nilais', NilaiController::class);

Route::get('/alternatifs', [AlternatifController::class, 'index'])->name('alternatifs.index');
Route::get('/kriterias', [KriteriaController::class, 'index'])->name('kriterias.index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('nilais', [App\Http\Controllers\NilaiController::class, 'index'])->name('nilais.index');


Route::get('nilais/create', [App\Http\Controllers\NilaiController::class, 'create'])->name('nilais.create');
Route::post('nilais', [App\Http\Controllers\NilaiController::class, 'store'])->name('nilais.store');
Route::get('nilais/{alternatif}/edit', [App\Http\Controllers\NilaiController::class, 'edit'])->name('nilais.edit'); // {alternatif} adalah ID alternatif
Route::put('nilais/{alternatif}', [App\Http\Controllers\NilaiController::class, 'update'])->name('nilais.update'); // {alternatif} adalah ID alternatif
Route::delete('nilais/{alternatif}', [App\Http\Controllers\NilaiController::class, 'destroy'])->name('nilais.destroy'); // {alternatif} adalah ID alternatif

Route::get('/uta-selection', [UtaController::class, 'index'])->name('uta.index');
Route::post('/uta-selection/calculate', [UtaController::class, 'calculate'])->name('uta.calculate');



Route::get('/', function () {
    return view('welcome');
});