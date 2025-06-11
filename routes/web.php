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

	Route::resource('manage-user', UserController::class)->middleware('admin');

	Route::prefix('preprocessing')->controller(PreprocessingController::class)->group(function () {
		Route::get('/', 'index')->name('preprocessing.index');
		Route::get('label', 'labeling')->name('preprocessing.label');
		Route::get('search', 'search')->name('preprocessing.search');
		Route::delete('delete-all', 'deleteAll')->name('preprocessing.delete.all');
		Route::get('label/edit/{id}', 'editLabel')->name('preprocessing.label.edit')->middleware('hasData');
		Route::put('label/update/{id}', 'updateLabel')->name('preprocessing.label.update');
		Route::post('train-data-size', 'trainData')->name('preprocessing.train-data');
		Route::post('split-data', 'splitData')->name('preprocessing.split-data');
		Route::get('import-csv', 'import')->name('preprocessing.import');
		Route::post('import-csv', 'storeImport')->name('preprocessing.import.store');
		Route::get('train-data', 'trainData')->name('preprocessing.train-data');
		Route::get('test-data', 'testData')->name('preprocessing.test-data');
	});

	Route::prefix('result')->middleware('hasSplit')->controller(ResultController::class)->group(function () {
		Route::get('bag-of-words', 'calculateBagOfWords')->name('result.bag-of-word');
		Route::get('naive-bayes', 'calculateNaiveBayes')->name('result.naive-bayes');
		Route::get('confusion-matrix', 'confusionMatrix')->name('result.confusion-matrix');
	});



	Route::prefix('my-profile')->group(function () {
		Route::get('/', [ProfileController::class, 'index'])->name('my-profile.index');
		Route::patch('update', [ProfileController::class, 'update'])->name('my-profile.update');
		Route::patch('update-password', [ProfileController::class, 'updatePassword'])->name('my-profile.update.password');
	});
});
