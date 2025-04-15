<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\PreprocessingController;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.process');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('dataset')->controller(DatasetController::class)->group(function () {
	Route::get('/', 'index')->name('dataset.index');
	Route::get('import', 'importCSV')->name('dataset.import');
	Route::post('import', 'importData')->name('dataset.import.process');
	Route::delete('delete-all', 'deleteAll')->name('dataset.delete-all');
});

Route::prefix('preprocessing')->controller(PreprocessingController::class)->group(function () {
	Route::get('/', 'index')->name('preprocessing.index');
});
