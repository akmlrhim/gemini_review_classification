<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\ResultController;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.process');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
	Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

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

	Route::prefix('result')->controller(ResultController::class)->group(function () {
		Route::get('naive-bayes', 'calculateNaiveBayes')->name('result.naive-bayes');
		Route::get('form-confusion-matrix', 'form')->name('result.confusion-matrix-form');
		Route::post('confusion-matrix-process', 'process')->name('result.confusion-matrix-process');
		Route::get('confusion-matrix', 'confusionMatrix')->name('result.confusion-matrix');
	});
});
