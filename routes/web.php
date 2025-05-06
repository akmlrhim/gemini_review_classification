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
	Route::get('contents', 'contents')->name('dataset.contents');
	Route::get('search', 'search')->name('dataset.search');
	Route::delete('delete-all', 'deleteAll')->name('dataset.delete.all');
});

Route::prefix('preprocessing')->controller(PreprocessingController::class)->group(function () {
	Route::get('/', 'index')->name('preprocessing.index');
	Route::get('label', 'labeling')->name('preprosesing.label');
	Route::get('search', 'search')->name('preprocessing.search');
	Route::delete('delete-all', 'deleteAll')->name('preprocessing.delete.all');
	Route::get('tf-idf', 'calculateTfIdf')->name('preprocessing.tfidf');
});
