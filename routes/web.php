<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\ChatController;

Broadcast::routes(['middleware' => ['web', 'auth']]);

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/start/{user}', [ChatController::class, 'startChat'])->name('chat.start');
    Route::get('/chat/{group}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{group}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
});

require __DIR__.'/auth.php';
