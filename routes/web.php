<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/posts', action: [PostController::class, 'index'])->name('posts.index');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// Chat routes - require authentication
Route::middleware(['auth'])->group(function () {
    // Chat interface
    Route::get('/chat', [MessageController::class, 'index'])->name('chat.index');

    // Send message
    Route::post('/chat', [MessageController::class, 'store'])->name('chat.store');

    // Get messages API for real-time updates
    Route::get('/api/messages', [MessageController::class, 'getMessages'])->name('chat.messages');

    // Mark messages as read
    Route::post('/api/messages/mark-read', [MessageController::class, 'markAsRead'])->name('chat.mark-read');
});
