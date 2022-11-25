<?php

use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\RevenuesController;
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
    Route::prefix('expenses')->controller(ExpensesController::class)->group(function () {
        Route::get('/', 'index')->name('expenses.index');
        Route::get('/create', 'create');
        Route::post('/', 'store')->name('expenses.store');
        Route::delete('/{expense}', 'delete');
    });
    
    Route::get('/revenues', [RevenuesController::class, 'index'])->name('revenues.index');
    Route::get('/revenues/create', [RevenuesController::class, 'create']);
    Route::post('/revenues', [RevenuesController::class, 'store'])->name('revenues.store');
});

require __DIR__.'/auth.php';
