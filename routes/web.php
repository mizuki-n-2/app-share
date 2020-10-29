<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\EditImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
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

Route::resource('posts', 'App\Http\Controllers\PostController');

Route::post('comment/{id}', [CommentController::class, 'store'])->name('comment.store');

Route::get('profile/{id}', [ProfileController::class, 'index'])->name('profile');
Route::get('profile/edit/{id}', [ProfileController::class, 'edit'])->name('edit');
Route::put('profile/update/{id}', [ProfileController::class, 'update'])->name('update');

Route::get('posts/like/{id}', [LikeController::class, 'like'])->name('like');
Route::get('posts/unlike/{id}', [LikeController::class, 'unlike'])->name('unlike');

Route::get('follow/{id}', [FollowController::class, 'follow'])->name('follow');
Route::get('unfollow/{id}', [FollowController::class, 'unfollow'])->name('unfollow');

Route::get('notification/index/{id}', [NotificationController::class, 'index'])->name('notification.index');
Route::delete('notification/delete/{id}', [NotificationController::class, 'delete'])->name('notification.delete');
Route::delete('notification/all_delete/{id}', [NotificationController::class, 'all_delete'])->name('notification.all_delete');

Route::get('ajax', [NotificationController::class, 'getData']);