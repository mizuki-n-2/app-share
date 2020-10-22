<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

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


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('posts', 'App\Http\Controllers\PostController')->except('index');

Route::post('comment/{id}', [CommentController::class, 'store'])->name('comment.store');

Route::get('profile/{id}', [ProfileController::class, 'index'])->name('profile');
Route::get('profile/edit/{id}', [ProfileController::class, 'edit'])->name('edit');
Route::put('profile/update/{id}', [ProfileController::class, 'update'])->name('update');

Route::get('posts/like/{id}', [LikeController::class, 'like'])->name('like');
Route::get('posts/unlike/{id}', [LikeController::class, 'unlike'])->name('unlike');