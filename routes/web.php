<?php

use App\Http\Controllers\InventoryPartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/inventory-parts', [InventoryPartController::class, 'index'])->name('inventory-parts.index');
    Route::get('/inventory-parts/create', [InventoryPartController::class, 'create'])->name('inventory-parts.create');
    Route::post('/inventory-parts', [InventoryPartController::class, 'store'])->name('inventory-parts.store');
    Route::get('/inventory-parts/{id}/edit', [InventoryPartController::class, 'edit'])->name('inventory-parts.edit');
    Route::put('/inventory-parts/{id}', [InventoryPartController::class, 'update'])->name('inventory-parts.update');
    Route::delete('/inventory-parts/{id}', [InventoryPartController::class, 'destroy'])->name('inventory-parts.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
