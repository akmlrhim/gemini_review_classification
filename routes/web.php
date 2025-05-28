<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
	return redirect()->route('dashboard.index');
});

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.process');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'saveRegister'])->name('register.process')->middleware('guest');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
	Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
	Route::delete('dashboard/reset', [DashboardController::class, 'reset'])->name('dashboard.reset');

	Route::resource('manage-user', UserController::class)->middleware('admin');

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
		Route::get('bag-of-words', 'calculateBagOfWords')->name('preprocessing.bag-of-word')->middleware('hasData');
		Route::get('label/edit/{id}', 'editLabel')->name('preprocessing.label.edit')->middleware('hasData');
		Route::put('label/update/{id}', 'updateLabel')->name('preprocessing.label.update');
	});

	Route::prefix('result')->middleware('hasData')->controller(ResultController::class)->group(function () {
		Route::get('naive-bayes', 'calculateNaiveBayes')->name('result.naive-bayes');
		Route::get('form-confusion-matrix', 'formInput')->name('result.confusion-matrix-form');
		Route::post('confusion-matrix-process', 'processFormInput')->name('result.confusion-matrix-process');
		Route::get('confusion-matrix', 'confusionMatrix')->name('result.confusion-matrix');
	});

	Route::get('import-csv', [ImportController::class, 'index'])->name('import.index');
	Route::post('import-csv', [ImportController::class, 'store'])->name('import.store');

	Route::prefix('my-profile')->group(function () {
		Route::get('/', [ProfileController::class, 'index'])->name('my-profile.index');
		Route::patch('update', [ProfileController::class, 'update'])->name('my-profile.update');
		Route::patch('update-password', [ProfileController::class, 'updatePassword'])->name('my-profile.update.password');
	});
});
