<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\PrintController;
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
		Route::get('label', 'label')->name('preprocessing.label');
		Route::delete('delete-all', 'deleteAll')->name('preprocessing.delete.all');
		Route::post('split-data', 'splitData')->name('preprocessing.split-data');
		Route::post('import-csv', 'import')->name('preprocessing.import');
		Route::get('train-data', 'trainData')->name('preprocessing.train-data');
		Route::get('test-data', 'testData')->name('preprocessing.test-data');
	});

	Route::prefix('result')->middleware('hasSplit')->controller(ResultController::class)->group(function () {
		Route::get('train-data', 'trainData')->name('result.train-data');
		Route::get('test-data', 'testData')->name('result.test-data');
		Route::get('naive-bayes', 'naiveBayes')->name('result.naive-bayes');
		Route::get('predicted-details', 'predictedDetails')->name('result.predicted-details');
		Route::get('confusion-matrix', 'confusionMatrix')->name('result.confusion-matrix');
	});

	Route::prefix('my-profile')->group(function () {
		Route::get('/', [ProfileController::class, 'index'])->name('my-profile.index');
		Route::patch('update', [ProfileController::class, 'update'])->name('my-profile.update');
		Route::patch('update-password', [ProfileController::class, 'updatePassword'])->name('my-profile.update.password');
	});

	Route::get('print-prp', [PrintController::class, 'preprocessing']);
	Route::get('print-train', [PrintController::class, 'trainData']);
	Route::get('print-test', [PrintController::class, 'testData']);
	Route::get('print-predicted', [PrintController::class, 'predictedDetails']);;
});
