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

Route::middleware(['auth', 'admin'])->group(function () {
	Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

	Route::prefix('dataset')->controller(DatasetController::class)->group(function () {
		Route::get('/', 'index')->name('dataset.index');
		Route::get('contents', 'contents')->name('dataset.contents');
		Route::get('search', 'search')->name('dataset.search');
		Route::delete('delete-all', 'deleteAll')->name('dataset.delete.all');
	});

	Route::prefix('preprocessing')->controller(PreprocessingController::class)->group(function () {
		Route::get('/', 'index')->name('preprocessing.index');
		Route::get('label', 'labeling')->name('preprocessing.label');
		Route::get('search', 'search')->name('preprocessing.search');
		Route::delete('delete-all', 'deleteAll')->name('preprocessing.delete.all');
		Route::get('tf-idf', 'calculateTfIdf')->name('preprocessing.tfidf')->middleware('labelled');
		Route::get('label/give/{id}', 'giveLabel')->name('preprocessing.label.give');
		Route::get('label/edit/{id}', 'editLabel')->name('preprocessing.label.edit');
		Route::put('label/update/{id}', 'updateLabel')->name('preprocessing.label.update');
		Route::put('nullable-label', 'nullableLabel')->name('preprocessing.nullable-label');
	});

	Route::prefix('result')->middleware('labelled')->controller(ResultController::class)->group(function () {
		Route::get('naive-bayes', 'calculateNaiveBayes')->name('result.naive-bayes');
		Route::get('form-confusion-matrix', 'formInput')->name('result.confusion-matrix-form');
		Route::post('confusion-matrix-process', 'processFormInput')->name('result.confusion-matrix-process');
		Route::get('confusion-matrix', 'confusionMatrix')->name('result.confusion-matrix');
	});
});
