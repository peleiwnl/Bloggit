<?php

use App\Http\Controllers\ProfileSettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\NewsController;

Route::redirect('/', '/home');

Route::get('/home', [HomeController::class, 'index'])->name('posts.index');


Route::middleware('auth')->group(function () {

    Route::get('/posts/create', [HomeController::class, 'create'])->name('posts.create');

    Route::post('/home', [HomeController::class, 'store'])->name('posts.store');

    Route::delete('/posts/{post}', [HomeController::class, 'destroy'])->name('posts.destroy');

    Route::get('/profile', [ProfileSettingsController::class, 'edit'])->name('profile.edit');

    Route::patch('/profile', [ProfileSettingsController::class, 'update'])->name('profile.update');

    Route::delete('/profile', [ProfileSettingsController::class, 'destroy'])->name('profile.destroy');

    Route::get('/posts/{post}/edit', [HomeController::class, 'edit'])->name('posts.edit');

    Route::patch('/posts/{post}', [HomeController::class, 'update'])->name('posts.update');
});


Route::get('/posts/{post}', [HomeController::class, 'show'])->name('posts.show');

Route::get('/users/{user}/profile', [ProfileController::class, 'show'])->name('users.show');

Route::get('/tags/{tag:name}', [TagController::class, 'show'])->name('tags.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');


require __DIR__ . '/auth.php';
