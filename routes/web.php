<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\SourceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('expenses')->controller(ExpenseController::class)->group(function () {
        Route::get('/', 'index')->name('expenses.index');
        Route::get('/create', 'create')->name('expenses.create');
        Route::post('/', 'store')->name('expenses.store');
        Route::delete('/{expense}', 'delete');
        Route::get("/{expense}/edit", 'edit');
        Route::patch("/{expense}", 'update')->name('expenses.update');
    });

    Route::prefix('revenues')->controller(RevenueController::class)->group(function () {
        Route::get('', 'index')->name('revenues.index');
        Route::get('/create', 'create');
        Route::post('', 'store')->name('revenues.store');
        Route::delete('/{revenue}', 'delete');
        Route::get("/{revenue}/edit", 'edit');
        Route::patch("/{revenue}", 'update')->name('revenues.update');
    });

    Route::prefix('categories')->controller(CategoryController::class)->group(function () {
        Route::get('/create', 'create');
        Route::post('', 'store')->name('categories.store');
        Route::get('', 'index')->name('categories.index');
        Route::delete('/{category}', 'delete');
    });

    Route::prefix('sources')->controller(SourceController::class)->group(function () {
        Route::get('/create', 'create');
        Route::post('', 'store')->name('sources.store');
        Route::get('', 'index')->name('sources.index');
        Route::delete('/{source}', 'delete');
    });
});

require __DIR__.'/auth.php';
