<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserRatingController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CommentReplyController;
use App\Http\Controllers\ReplyLikeController;
use App\Http\Controllers\ReplyToReplyController;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\PostRepostController;
use App\Http\Controllers\Admin\BotDebugController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\Admin\LogController;

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Статические страницы
Route::get('/rules', [PageController::class, 'rules'])->name('rules');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::get('/help', [PageController::class, 'help'])->name('help');

// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

// Защищенные маршруты
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Профиль
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Уведомления
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    
    // Закладки
    Route::get('bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::delete('bookmarks/clear', [BookmarkController::class, 'clear'])->name('bookmarks.clear');
    Route::delete('bookmarks/{post}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
    Route::post('posts/{post}/bookmark', [PostController::class, 'bookmark'])->name('posts.bookmark');
    
    // Рейтинги
    Route::post('posts/{post}/rate', [PostController::class, 'rate'])->name('posts.rate');
    
    // Комментарии
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
    Route::post('answers/{answer}/comments', [CommentController::class, 'storeAnswerComment'])->name('answers.comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentLikeController::class, 'toggle'])->name('comments.like')->middleware('auth');
    Route::post('comments/{comment}/dislike', [CommentController::class, 'dislike'])->name('comments.dislike');
    Route::post('comments/{comment}/replies', [CommentReplyController::class, 'store'])->name('comments.replies.store');
    Route::put('comments/replies/{reply}', [CommentReplyController::class, 'update'])->name('comments.replies.update');
    Route::delete('comments/replies/{reply}', [CommentReplyController::class, 'destroy'])->name('comments.replies.destroy');
    Route::delete('replies/{reply}', [CommentReplyController::class, 'destroy'])->name('replies.destroy');
    Route::post('/replies/{reply}/like', [ReplyLikeController::class, 'toggle'])->name('replies.like')->middleware('auth');
    Route::post('/replies/{reply}/replies', [ReplyToReplyController::class, 'store'])->name('replies.replies.store');
    Route::patch('comments/{comment}', [CommentController::class, 'update'])->name('comments.update.patch');
    Route::patch('comments/replies/{reply}', [CommentReplyController::class, 'update'])->name('comments.replies.update.patch');

    Route::get('drafts', [DraftController::class, 'index'])->name('drafts.index');
    Route::get('drafts/{post}', [DraftController::class, 'show'])->name('drafts.show');

    // Жалобы
    Route::middleware(['auth'])->group(function () {
        Route::post('posts/{post}/report', [ReportController::class, 'reportPost'])->name('posts.report');
        Route::post('comments/{comment}/report', [ReportController::class, 'reportComment'])->name('comments.report');
        Route::post('replies/{reply}/report', [ReportController::class, 'reportReply'])->name('replies.report');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    });

    // Админ панель
    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::delete('admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('admin/users/{id}/restore', [AdminUserController::class, 'restore'])->name('admin.users.restore');
    Route::put('admin/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::get('admin/posts', [AdminPostController::class, 'index'])->name('admin.posts.index');
    Route::delete('admin/posts/{post}', [AdminPostController::class, 'destroy'])->name('admin.posts.destroy');
    Route::get('admin/tags', [AdminTagController::class, 'index'])->name('admin.tags.index');
    Route::delete('admin/tags/{tag}', [AdminTagController::class, 'destroy'])->name('admin.tags.destroy');
    Route::get('admin/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');

    // Маршруты для управления жалобами в админке
    Route::get('admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::get('admin/complaints/{complaint}', [ComplaintController::class, 'show'])->name('admin.complaints.show');
    Route::post('admin/complaints/{complaint}', [ComplaintController::class, 'update'])->name('admin.complaints.update');
    Route::delete('admin/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('admin.complaints.destroy');

    // Маршруты для просмотра логов системы в админке
    Route::get('admin/logs', [LogController::class, 'index'])->name('admin.logs.index');
});

// Посты
Route::get('posts', [PostController::class, 'index'])->name('posts.index');
Route::get('questions', function (Request $request) {
    return app(PostController::class)->index($request->merge(['type' => 'question']));
})->name('questions.index');

Route::middleware('auth')->group(function () {
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('drafts', [DraftController::class, 'index'])->name('drafts.index');
    Route::get('drafts/{post}', [DraftController::class, 'show'])->name('drafts.show');
});

Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{post}/like', [PostLikeController::class, 'toggle'])->middleware('auth')->name('posts.like');
Route::post('posts/{post}/bookmark', [PostController::class, 'bookmark'])->name('posts.bookmark');
Route::post('/posts/{post}/repost', [PostRepostController::class, 'toggle'])->name('posts.repost')->middleware('auth');

// Пользователи
Route::get('users/rating', [UserController::class, 'rating'])->name('users.rating');
Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

// Теги
Route::get('tags', [TagController::class, 'index'])->name('tags.index');
Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');

// Поиск
Route::get('search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/posts', [SearchController::class, 'searchPosts'])->name('search.posts');

Route::get('/answers', [AnswerController::class, 'index'])->name('answers.index');
Route::get('/answers/unread', [AnswerController::class, 'getUnreadCount'])->name('answers.unread');

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/viewed', [NotificationController::class, 'markAsViewed'])->name('notifications.viewed');
    Route::post('/notifications/unviewed-count', function() {
        if (!request()->ajax()) {
            return redirect()->route('home');
        }
        
        $count = auth()->user()->notifications()
            ->where('viewed', false)
            ->count();
            
        return response()->json(['count' => $count]);
    })->name('notifications.unviewed-count');
});

// Маршруты для отладки бота
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/bot-debug', [BotDebugController::class, 'index'])->name('bot-debug.index');
    Route::post('/bot-debug/test', [BotDebugController::class, 'test'])->name('bot-debug.test');
});

// Маршруты для модератора
Route::middleware(['auth'])->prefix('moderator')->name('moderator.')->group(function () {
    // Основные маршруты модератора
    Route::get('/', [ModeratorController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [ModeratorController::class, 'users'])->name('users');
    Route::get('/content', [ModeratorController::class, 'content'])->name('content');
    
    // Маршруты для управления контентом
    Route::post('/posts/{post}/hide', [ModeratorController::class, 'hidePost'])->name('posts.hide');
    Route::post('/comments/{comment}/hide', [ModeratorController::class, 'hideComment'])->name('comments.hide');
    Route::post('users/{user}/restrict', [ModeratorController::class, 'restrictUser'])->name('users.restrict');
    Route::delete('/users/{user}', [ModeratorController::class, 'deleteUser'])->name('users.delete');
    
    // Маршруты для жалоб
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::delete('/posts/{post}', [\App\Http\Controllers\ModeratorController::class, 'deletePost'])->name('posts.delete');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\ModeratorController::class, 'deleteComment'])->name('comments.delete');
});
