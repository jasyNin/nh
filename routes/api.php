<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

// Публичные маршруты
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Защищенные маршруты
Route::middleware('auth:sanctum')->group(function () {
    // Аутентификация
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Посты
    Route::apiResource('posts', PostController::class);
    Route::get('/drafts', [PostController::class, 'drafts']);

    // Комментарии
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'update']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

    // Закладки
    Route::get('bookmarks', [BookmarkController::class, 'index']);
    Route::post('posts/{post}/bookmarks', [BookmarkController::class, 'store']);
    Route::delete('bookmarks/{bookmark}', [BookmarkController::class, 'destroy']);

    // Рейтинг
    Route::post('posts/{post}/ratings', [RatingController::class, 'store']);
    Route::delete('ratings/{rating}', [RatingController::class, 'destroy']);

    // Уведомления
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::put('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::put('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
}); 