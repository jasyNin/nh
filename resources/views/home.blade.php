@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<style>
    /* Основные стили для главной страницы */
    .right-sidebar {
        margin-top: 20px;
    }
    .comment-textarea {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        resize: none;
    }
    .comment-submit-btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        background-color: #1682FD;
        color: white;
        font-size: 14px;
        padding: 6px 16px;
    }
    .card-header {
        background-color: transparent;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }
    .nav-tabs {
        border-bottom: none;
    }
    .nav-tabs .nav-link.active {
        color: #1682FD;
        border: none;
        border-bottom: 2px solid #1682FD;
        background-color: transparent;
    }
    
    /* Стили для комментариев */
    .comments-container {
        border-top: 1px solid #eee;
        padding-top: 15px;
        margin-top: 15px;
        display: none;
    }
    .comment {
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    .comment:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    .comment-content {
        margin: 8px 0;
        font-size: 14px;
    }
    .comment-form-container {
        margin-top: 15px;
    }
    .input-group {
        border-radius: 8px;
        overflow: hidden;
    }
    .replies-toggle {
        color: #1682FD;
        cursor: pointer;
        font-size: 15px;
        font-weight: normal;
    }
    .replies-toggle:hover {
        text-decoration: underline !important;
    }
    .replies-toggle.active {
        font-weight: bold;
    }
    
    /* Стили для кнопок взаимодействия */
    .like-button, .comment-button, .repost-button, .bookmark-button {
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .like-button::after, .comment-button::after, .repost-button::after, .bookmark-button::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background-color: rgba(22, 130, 253, 0.1);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease;
    }
    
    .like-button:hover::after, .comment-button:hover::after, .repost-button:hover::after, .bookmark-button:hover::after {
        width: 100px;
        height: 100px;
    }
    
    .like-button:active::after, .comment-button:active::after, .repost-button:active::after, .bookmark-button:active::after {
        width: 120px;
        height: 120px;
    }
    
    .like-button:hover, .comment-button:hover, .repost-button:hover, .bookmark-button:hover {
        opacity: 0.8;
    }
    
    .like-button.active, .comment-button.active, .repost-button.active, .bookmark-button.active {
        transform: scale(1.05);
    }
    
    /* Стили для форм ответов */
    .reply-form-container, .reply-to-reply-form-container {
        margin-top: 10px;
        margin-left: 20px;
        display: none;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(-10px);
    }
    
    .reply-form-container.show, .reply-to-reply-form-container.show {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Стили для модального окна жалоб */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        border-bottom: 1px solid #eee;
        padding: 1rem 1.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #eee;
        padding: 1rem 1.5rem;
    }
    
    .form-select:focus,
    .form-control:focus {
        border-color: #1682FD;
        box-shadow: 0 0 0 0.2rem rgba(22, 130, 253, 0.25);
    }
    
    /* Стили для уведомлений */
    .toast-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        padding: 12px 24px;
        border-radius: 8px;
        background-color: #28a745;
        color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .toast-message.show {
        opacity: 1;
    }
    
    .toast-message.error {
        background-color: #dc3545;
    }
    
    /* Стили для ответов */
    .reply,
    .reply-to-reply {
        position: relative;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .reply:hover,
    .reply-to-reply:hover {
        background-color: #f1f3f5;
    }
    
    .reply::before,
    .reply-to-reply::before {
        content: '';
        position: absolute;
        left: -2px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e5e7eb;
        transition: background-color 0.3s ease;
    }
    
    .reply:hover::before,
    .reply-to-reply:hover::before {
        background-color: #1682FD;
    }
</style>
<div class="container" style="margin-top: 60px;">
    @if(isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif

    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент (посты) -->
        <div class="col-md-7">
            <div class="card border-0 bg-transparent">
                <div class="card-header bg-transparent border-0">
                    <ul class="nav nav-tabs card-header-tabs border-0">
                        <li class="nav-item" >
                            <a class="nav-link {{ !request('type') ? 'active' : '' }}" href="{{ route('home') }}">
                                Все
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('type') === 'post' ? 'active' : '' }}" href="{{ route('home', ['type' => 'post']) }}">
                                Записи
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('type') === 'question' ? 'active' : '' }}" href="{{ route('home', ['type' => 'question']) }}">
                                Вопросы
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if($posts->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/home.svg') }}" alt="Постов пока нет" width="48" height="48" class="mb-3">
                            <h5 class="fw-light mb-3">Постов пока нет</h5>
                            <p class="text-muted mb-4">Создайте свой первый пост, чтобы начать</p>
                            @auth
                            <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
                                Создать пост
                            </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">
                                    Войти для создания поста
                                </a>
                            @endauth
                        </div>
                    @else
                        <div class="posts-container">
                            @foreach($posts as $post)
                                <div class="post-card">
                                    <div class="card border-0 hover-card">
                                        <div class="card-body p-4">
                                            <!-- Информация о пользователе -->
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="me-2">
                                                    <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none">
                                                        <x-user-avatar :user="$post->user" :size="40" />
                                                    </a>
                                                </div>
                                                <div>
                                                    <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none text-dark">
                                                        <h6 class="mb-0">{{ $post->user->name }}</h6>
                                                    </a>
                                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                                </div>
                                                <div class="ms-auto d-flex align-items-center">
                                                    <span class="badge bg-{{ $post->type === 'post' ? 'primary' : 'success' }} rounded-pill px-3 py-1 me-2">
                                                        {{ $post->type === 'post' ? 'Запись' : 'Вопрос' }}
                                                    </span>
                                                    
                                                    <!-- Добавляем меню управления -->
                                                    @auth
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                                <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                @if(auth()->id() === $post->user_id)
                                                                <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">Редактировать</a></li>
                                                                <li>
                                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот пост?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger">Удалить</button>
                                                                    </form>
                                                                </li>
                                                            @else
                                                                <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#reportPostModal{{ $post->id }}">Пожаловаться</a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    @endauth
                                                </div>
                                            </div>

                                            <!-- Заголовок и контент -->
                                            <div class="post-content">
                                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                                    <h5 class="card-title mb-3 text-dark">
                                                        {{ $post->title }}
                                                    </h5>
                                                    
                                                    <p class="card-text text-muted mb-3">{{ Str::limit($post->content, 200) }}</p>

                                                    <!-- Изображение, если есть -->
                                                    @if($post->image)
                                                        <div class="post-image mb-3">
                                                            <img src="{{ asset('storage/' . $post->image) }}" 
                                                                 class="img-fluid rounded" 
                                                                 alt="{{ $post->title }}">
                                                        </div>
                                                    @endif

                                                    <!-- Теги -->
                                                    @if($post->tags->isNotEmpty())
                                                        <div class="tags mb-3">
                                                            @foreach($post->tags as $tag)
                                                                <a href="{{ route('tags.show', $tag) }}" 
                                                                   class="badge bg-light text-dark text-decoration-none me-1">
                                                                    #{{ $tag->name }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </a>
                                            </div>

                                            <!-- Действия с постом -->
                                            <div class="d-flex align-items-center justify-content-between mb-4">
                                                <div class="d-flex align-items-center">
                                                    @auth
                                                    <button class="btn btn-link text-dark p-0 me-4 like-button" data-post-id="{{ $post->id }}">
                                                        <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="{{ $post->likedBy(auth()->user()) ? 'liked' : '' }}">
                                                        <span class="ms-1 likes-count" style="pointer-events: none;">{{ $post->likes_count }}</span>
                                                    </button>
                                                    @else
                                                    <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 me-4">
                                                        <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16">
                                                        <span class="ms-1">{{ $post->likes_count }}</span>
                                                    </a>
                                                    @endauth

                                                    <button class="btn btn-link text-dark p-0 me-4 comment-toggle">
                                                        <img src="{{ asset('images/comment.svg') }}" alt="Комментарии" width="20" height="19">
                                                        <span class="ms-1">{{ $post->comments_count }}</span>
                                                    </button>

                                                    <button class="btn btn-link text-dark p-0 me-4 repost-button" id="copy-post-link">
                                                        <img src="{{ asset('images/reply.svg') }}" alt="Поделиться" width="20" height="21">
                                                        <span class="ms-1">{{ $post->reposts_count }}</span>
                                                    </button>
                                            </div>

                                                @auth
                                                <form action="{{ route('posts.bookmark', $post) }}" method="POST" class="ms-auto">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-dark p-0 bookmark-button {{ $post->isBookmarkedBy(auth()->user()) ? 'active' : '' }}">
                                                        <img src="{{ asset('images/bookmark-mini.svg') }}" alt="Закладка" width="20" height="20" class="{{ $post->isBookmarkedBy(auth()->user()) ? 'bookmarked' : '' }}">
                                                    </button>
                                                </form>
                                                @else
                                                <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 ms-auto">
                                                    <img src="{{ asset('images/bookmark-mini.svg') }}" alt="Закладка" width="20" height="20">
                                                </a>
                                                @endauth
                                                                    </div>

                                            <!-- Комментарии -->
                                            <div class="comments-section mb-4">
                                                <h6 class="mb-3">{{ $post->comments_count }} {{ __('posts.comments.' . min($post->comments_count, 20)) }}</h6>
                                                
                                                <div class="comments-container" id="comments-container-{{ $post->id }}">
                                                    @auth
                                                        <div class="comment-form-container mt-4">
                                                            <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="comment-form">
                                                                @csrf
                                                                <div class="input-group">
                                                                    <textarea name="content" class="form-control comment-textarea" rows="1" placeholder="Комментарий..."></textarea>
                                                                    <button type="submit" class="btn btn-primary comment-submit-btn">
                                                                        Отправить
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    @else
                                                        <div class="text-center py-3 mt-3">
                                                            <p class="mb-0">Чтобы оставить комментарий, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a></p>
                                                    </div>
                                                    @endauth
                                                
                                                    <!-- Список комментариев -->
                                                <div class="comments-list">
                                                        @foreach($post->comments->take(5) as $comment)
                                                            <div class="comment" id="comment-{{ $comment->id }}">
                                                                <div class="d-flex">
                                                                    <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none me-2">
                                                                        <x-user-avatar :user="$comment->user" :size="32" />
                                                                        </a>
                                                                    <div class="flex-grow-1">
                                                                            <div class="d-flex align-items-center">
                                                                            <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none text-dark fw-bold me-2">{{ $comment->user->name }}</a>
                                                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                                            </div>
                                                                        <div class="comment-content">{{ $comment->content }}</div>
                                                                        
                                                                        <div class="d-flex align-items-center mt-2">
                                                                            <div class="d-flex align-items-center me-3 like-button" data-comment-id="{{ $comment->id }}">
                                                                                <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="16" height="14" class="me-1 {{ auth()->check() && $comment->likedBy(auth()->user()) ? 'liked' : '' }}">
                                                                                <span class="likes-count {{ auth()->check() && $comment->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $comment->likes_count }}</span>
                                                                            </div>
                                                                            
                                                                            <div class="replies-toggle" data-comment-id="{{ $comment->id }}">
                                                                                {{ $comment->replies_count }} {{ __('posts.replies.' . min($comment->replies_count, 20)) }}
                                                                            </div>
                                                                            
                                                                            <button class="btn btn-link text-dark p-0 ms-2 reply-button" data-comment-id="{{ $comment->id }}">
                                                                                Ответить
                                                                            </button>
                                                                            
                                                                            <button class="btn btn-link text-dark p-0 ms-2" data-bs-toggle="modal" data-bs-target="#reportCommentModal{{ $comment->id }}">
                                                                                Пожаловаться
                                                                            </button>
                                                                        </div>
                                                                        
                                                                        <!-- Форма ответа на комментарий -->
                                                                        <div class="reply-form-container" id="reply-form-{{ $comment->id }}">
                                                                            <form action="{{ route('comments.replies.store', $comment) }}" method="POST" class="reply-form">
                                                                                @csrf
                                                                                <div class="input-group">
                                                                                    <textarea name="content" class="form-control reply-textarea" rows="1" placeholder="Ответить..."></textarea>
                                                                                    <button type="submit" class="btn btn-primary reply-submit-btn">
                                                                                        Отправить
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        
                                                                        <!-- Ответы на комментарий -->
                                                                        <div class="replies-container" id="replies-{{ $comment->id }}">
                                                                            @if($comment->replies && $comment->replies->count() > 0)
                                                                                @foreach($comment->replies->take(3) as $reply)
                                                                                    <div class="reply p-4 border-b border-gray-200" id="reply-{{ $reply->id }}">
                                                                                        <div class="flex items-center mb-2">
                                                                                            <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-8 h-8 rounded-full mr-2">
                                                                                            <span class="font-semibold">{{ $reply->user->name }}</span>
                                                                                            <span class="text-gray-500 text-sm ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                                                                        </div>
                                                                                        <p class="text-gray-800">{{ $reply->content }}</p>
                                                                                        <div class="flex items-center mt-2">
                                                                                            <button class="like-button text-gray-500 hover:text-pink-500 focus:outline-none {{ auth()->check() && $reply->likedBy(auth()->user()) ? 'text-pink-500' : '' }}" data-id="{{ $reply->id }}">
                                                                                                <i class="far fa-heart"></i>
                                                                                                <span class="like-count">{{ $reply->likes()->count() }}</span>
                                                                                            </button>
                                                                                            <button class="reply-to-reply-button text-gray-500 hover:text-blue-500 focus:outline-none ml-4" data-reply-id="{{ $reply->id }}">
                                                                                                <i class="far fa-comment"></i>
                                                                                                <span>Ответить</span>
                                                                                            </button>
                                                                                            
                                                                                            <button class="text-gray-500 hover:text-red-500 focus:outline-none ml-4" data-bs-toggle="modal" data-bs-target="#reportReplyModal{{ $reply->id }}">
                                                                                                <i class="far fa-flag"></i>
                                                                                                <span>Пожаловаться</span>
                                                                                            </button>
                                                                                                </div>
                                                                                        
                                                                                        <!-- Форма для ответа на ответ -->
                                                                                        <div id="reply-to-reply-form-{{ $reply->id }}" class="reply-to-reply-form-container mt-4 hidden">
                                                                                            <form class="reply-to-reply-form" data-reply-id="{{ $reply->id }}">
                                                                                                <textarea name="content" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="2" placeholder="Напишите ваш ответ..."></textarea>
                                                                                                <div class="flex justify-end mt-2">
                                                                                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none">
                                                                                                        Отправить
                                                                                                    </button>
                                                                                                </div>
                                                                                                                </form>
                                                                                                </div>

                                                                                        <!-- Контейнер для ответов на ответ -->
                                                                                        <div id="replies-{{ $reply->id }}" class="replies-container mt-4">
                                                                                            @if($reply->replies && $reply->replies->count() > 0)
                                                                                                @foreach($reply->replies as $replyToReply)
                                                                                                    <div class="reply-to-reply p-4 ml-8 border-l-2 border-gray-200" id="reply-to-reply-{{ $replyToReply->id }}">
                                                                                                        <div class="flex items-center mb-2">
                                                                                                            <img src="{{ $replyToReply->user->avatar_url }}" alt="{{ $replyToReply->user->name }}" class="w-8 h-8 rounded-full mr-2">
                                                                                                            <span class="font-semibold">{{ $replyToReply->user->name }}</span>
                                                                                                            <span class="text-gray-500 text-sm ml-2">{{ $replyToReply->created_at->diffForHumans() }}</span>
                                                                                            </div>
                                                                                                        <p class="text-gray-800">{{ $replyToReply->content }}</p>
                                                                                                        <div class="flex items-center mt-2">
                                                                                                            <button class="like-button text-gray-500 hover:text-pink-500 focus:outline-none {{ auth()->check() && $replyToReply->likedBy(auth()->user()) ? 'text-pink-500' : '' }}" data-id="{{ $replyToReply->id }}">
                                                                                                                <i class="far fa-heart"></i>
                                                                                                                <span class="like-count">{{ $replyToReply->likes()->count() }}</span>
                                                                                                            </button>
                                                                                            </div>
                                                                                                </div>
                                                                                                @endforeach
                                                                                            @endif
                                                                                                </div>
                                                                                            </div>
                                                                                @endforeach
                                                                            @endif
                                                                            
                                                                            @if($comment->replies_count > 3)
                                                                                <div class="text-center mt-2">
                                                                                    <button class="btn btn-link text-primary load-more-replies" data-comment-id="{{ $comment->id }}">
                                                                                        Показать еще {{ $comment->replies_count - 3 }} {{ trans_choice('posts.replies', $comment->replies_count - 3) }}
                                                                                                        </button>
                                                                                                    </div>
                                                                            @endif
                                                                                                    </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                    </div>
                                                    
                                                    @if($post->comments_count > 5)
                                                        <div class="text-center mt-4">
                                                            <button class="btn btn-link text-primary load-more-comments" data-post-id="{{ $post->id }}">
                                                                Показать еще {{ $post->comments_count - 5 }} {{ trans_choice('posts.comments', $post->comments_count - 5) }}
                                                            </button>
                                                                            </div>
                                                                        @endif
                                                </div>
                                            </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                        </div>

                        <!-- Пагинация -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $posts->links() }}
                                                            </div>
                                                        @endif
                                                        </div>
            </div>
                                                </div>
                                                
        <!-- Правая боковая панель -->
        <div class="col-md-3 right-sidebar" style="margin-top: 20px;">
                                                @auth
                @php
                    $viewedPosts = auth()->user()->viewedPosts()->take(5)->get();
                @endphp
                @if($viewedPosts->isNotEmpty())
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="card-title fw-bold mb-0">История просмотров</h6>
                                                            </div>
                        <div class="list-group list-group-flush">
                            @foreach($viewedPosts as $post)
                                <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action border-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="list-title text-truncate me-3">{{ $post->title }}</div>
                                        <small class="text-muted">{{ $post->type === 'post' ? 'Запись' : 'Вопрос' }}</small>
                                                    </div>
                                </a>
                            @endforeach
                                                    </div>
                    </div>
                @endif
                                                @endauth

            @if(count($popularTags) > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="card-title fw-bold mb-0">Популярные теги</h6>
                                            </div>
                    <div class="card-body pt-0">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($popularTags->take(6) as $tag)
                                <a href="{{ route('tags.show', $tag) }}" class="tag-badge">
                                    #{{ $tag->name }}
                                    <span class="tag-count">{{ $tag->posts_count }}</span>
                                </a>
                            @endforeach
                                        </div>
                                    </div>
                                </div>
            @endif

            @if(count($topUsers) > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="card-title fw-bold mb-0">Топ пользователей</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($topUsers->take(3) as $user)
                            <a href="{{ route('users.show', $user) }}" class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <x-user-avatar :user="$user" :size="40" class="me-3" style="margin-right: 12px !important;" />
                                    <div style="margin-left: 12px;">
                                        <div class="user-name fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->rating ?? $user->posts_count }} {{ isset($user->rating) ? __('rating.points') : __('posts.posts.' . min($user->posts_count, 20)) }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(count($recentAnswers) > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="card-title fw-bold mb-0">Последние ответы</h6>
                </div>
                    <div class="list-group list-group-flush">
                        @foreach($recentAnswers->take(3) as $answer)
                            <a href="{{ route('posts.show', $answer->post) }}" class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="list-title">{{ Str::limit($answer->post->title, 40) }}</div>
                                    <small class="text-muted">{{ $answer->created_at->diffForHumans() }}</small>
            </div>
                                <small class="text-muted d-block">{{ $answer->user->name }}</small>
                            </a>
                        @endforeach
        </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функция для показа уведомлений
    function showToast(message, type = 'success') {
        const toast = document.querySelector('.toast-message');
        toast.textContent = message;
        toast.className = `toast-message ${type}`;
        toast.style.display = 'block';
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.style.display = 'none';
            }, 300);
        }, 2000);
    }

    // Обработка лайков
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            @auth
        const postId = this.dataset.postId;
            const commentId = this.dataset.commentId;
            const replyId = this.dataset.replyId;
            
            let url;
            if (postId) {
                url = `/posts/${postId}/like`;
            } else if (commentId) {
                url = `/comments/${commentId}/like`;
            } else if (replyId) {
                url = `/replies/${replyId}/like`;
            }
            
        const likesCount = this.querySelector('.likes-count');
            const img = this.querySelector('img');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (likesCount) {
                    likesCount.textContent = data.likes_count;
                if (data.liked) {
                        likesCount.classList.add('liked');
                } else {
                        likesCount.classList.remove('liked');
                }
            }
            
                if (img) {
                if (data.liked) {
                        img.classList.add('liked');
                } else {
                        img.classList.remove('liked');
                }
            }
            
            if (data.liked) {
                this.classList.add('active');
            } else {
                this.classList.remove('active');
            }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка при обработке лайка', 'error');
            });
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        });
    });

    // Обработка комментариев
    document.querySelectorAll('.comment-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const commentsContainer = document.getElementById(`comments-container-${postId}`);
            
            if (commentsContainer.style.display === 'none' || !commentsContainer.style.display) {
                commentsContainer.style.display = 'block';
                this.classList.add('active');
                
                setTimeout(() => {
                    commentsContainer.classList.add('show');
                }, 10);
            } else {
                commentsContainer.classList.remove('show');
                setTimeout(() => {
                    commentsContainer.style.display = 'none';
                }, 300);
                
                    this.classList.remove('active');
            }
        });
    });
    
    // Обработка репостов
    document.querySelectorAll('.repost-button').forEach(button => {
        button.addEventListener('click', function() {
            const postUrl = window.location.href;
            navigator.clipboard.writeText(postUrl).then(() => {
                showToast('Ссылка на пост скопирована');
                this.classList.add('active');
                setTimeout(() => {
                    this.classList.remove('active');
                }, 300);
            }).catch(() => {
                showToast('Не удалось скопировать ссылку', 'error');
            });
        });
    });

    // Обработка закладок
    document.querySelectorAll('.bookmark-button').forEach(button => {
        button.addEventListener('click', function() {
            @auth
            const postId = this.dataset.postId;
            
            fetch(`/posts/${postId}/bookmark`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                const img = this.querySelector('img');
                if (data.bookmarked) {
                    this.classList.add('active');
                    img.classList.add('bookmarked');
                    showToast('Пост добавлен в закладки');
                                    } else {
                this.classList.remove('active');
                    img.classList.remove('bookmarked');
                    showToast('Пост удален из закладок');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка при обработке закладки', 'error');
            });
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        });
    });
    
    // Обработка форм жалоб
    document.querySelectorAll('.complaint-form').forEach(form => {
        form.addEventListener('submit', function(e) {
        e.preventDefault();
            
            @auth
            const complaintableId = this.dataset.complaintableId;
            const complaintableType = this.dataset.complaintableType;
            const type = this.querySelector('select[name="type"]').value;
            const reason = this.querySelector('textarea[name="reason"]').value;
            
            if (!type || !reason) {
                showToast('Пожалуйста, заполните все поля', 'error');
            return;
        }
        
            fetch('{{ route("complaints.store") }}', {
                    method: 'POST',
                    headers: {
                    'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                },
                body: JSON.stringify({
                    complaintable_id: complaintableId,
                    complaintable_type: complaintableType,
                    type: type,
                    reason: reason
                })
                })
                .then(response => response.json())
                .then(data => {
                showToast(data.message);
                
                // Закрываем модальное окно
                const modal = this.closest('.modal');
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
                
                // Очищаем форму
                this.reset();
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка при отправке жалобы', 'error');
            });
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        });
    });
});
</script>
@endpush

<!-- Модальные окна для жалоб -->
@foreach($posts as $post)
<div class="modal fade" id="reportPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="reportPostModalLabel{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="border-bottom: none;">
                <h5 class="modal-title" id="reportPostModalLabel{{ $post->id }}">Пожаловаться на пост</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="complaint-form" data-complaintable-id="{{ $post->id }}" data-complaintable-type="App\\Models\\Post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="complaintType{{ $post->id }}" class="form-label">Тип жалобы</label>
                        <select class="form-select" id="complaintType{{ $post->id }}" name="type" required>
                            <option value="">Выберите тип жалобы</option>
                            <option value="spam">Спам</option>
                            <option value="inappropriate">Неприемлемый контент</option>
                            <option value="violence">Насилие</option>
                            <option value="copyright">Нарушение авторских прав</option>
                            <option value="other">Другое</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="complaintReason{{ $post->id }}" class="form-label">Причина жалобы</label>
                        <textarea class="form-control" id="complaintReason{{ $post->id }}" name="reason" rows="3" required minlength="10" placeholder="Опишите подробнее причину жалобы..."></textarea>
                        <div class="form-text">Минимум 10 символов</div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <div class="d-flex justify-content-between w-100">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn" style="background-color: #1682FD; color: white;">Отправить жалобу</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@foreach($posts as $post)
    @foreach($post->comments as $comment)
    <div class="modal fade" id="reportCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="reportCommentModalLabel{{ $comment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportCommentModalLabel{{ $comment->id }}">Пожаловаться на комментарий</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                <form class="complaint-form" data-complaintable-id="{{ $comment->id }}" data-complaintable-type="App\\Models\\Comment">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="complaintType{{ $comment->id }}" class="form-label">Тип жалобы</label>
                            <select class="form-select" id="complaintType{{ $comment->id }}" name="type" required>
                                <option value="">Выберите тип жалобы</option>
                                <option value="spam">Спам</option>
                                <option value="inappropriate">Неприемлемый контент</option>
                                <option value="violence">Насилие</option>
                                <option value="copyright">Нарушение авторских прав</option>
                                <option value="other">Другое</option>
                            </select>
                                    </div>
                        <div class="mb-3">
                            <label for="complaintReason{{ $comment->id }}" class="form-label">Описание жалобы</label>
                            <textarea class="form-control" id="complaintReason{{ $comment->id }}" name="reason" rows="3" required minlength="10" placeholder="Опишите причину жалобы (минимум 10 символов)"></textarea>
                                    </div>
                                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Отправить жалобу</button>
                                </div>
                </form>
                                    </div>
                                    </div>
                                </div>
    @endforeach
@endforeach

<!-- Модальные окна для жалоб на ответы -->
@foreach($posts as $post)
    @foreach($post->comments as $comment)
        @foreach($comment->replies as $reply)
        <div class="modal fade" id="reportReplyModal{{ $reply->id }}" tabindex="-1" aria-labelledby="reportReplyModalLabel{{ $reply->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportReplyModalLabel{{ $reply->id }}">Пожаловаться на ответ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                    <form class="complaint-form" data-complaintable-id="{{ $reply->id }}" data-complaintable-type="App\\Models\\Reply">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="complaintType{{ $reply->id }}" class="form-label">Тип жалобы</label>
                                <select class="form-select" id="complaintType{{ $reply->id }}" name="type" required>
                                    <option value="">Выберите тип жалобы</option>
                                    <option value="spam">Спам</option>
                                    <option value="inappropriate">Неприемлемый контент</option>
                                    <option value="violence">Насилие</option>
                                    <option value="copyright">Нарушение авторских прав</option>
                                    <option value="other">Другое</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="complaintReason{{ $reply->id }}" class="form-label">Описание жалобы</label>
                                <textarea class="form-control" id="complaintReason{{ $reply->id }}" name="reason" rows="3" required minlength="10" placeholder="Опишите причину жалобы (минимум 10 символов)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary">Отправить жалобу</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
        </div>
        @endforeach
    @endforeach
@endforeach
@endsection 