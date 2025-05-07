@extends('layouts.app')

@section('title', 'Мой профиль')

@section('content')
<style>
    /* Стили для постов на странице профиля */
    .post-card {
        margin-bottom: 20px;
    }
    .hover-card {
        background-color: transparent;
        border: none !important;
    }
    .hover-card:hover {
        transform: none;
        box-shadow: none !important;
    }
    .card-title {
        font-size: 1.1rem;
    }
    .card-title a:hover {
        color: #1682FD !important;
    }
    .badge {
        font-weight: 400;
        font-size: 0.8rem;
    }
    .card-text {
        line-height: 1.5;
    }
    .btn-primary {
        background-color: #1682FD;
        border-color: #1682FD;
    }
    .btn-primary:hover {
        background-color: #1470e0;
        border-color: #1470e0;
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
    
    /* Стили для кнопок взаимодействия */
    .like-button, .comment-button, .repost-button, .bookmark-button {
        cursor: pointer;
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
        
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.3s ease, height 0.3s ease;
    }
    
    .like-button:hover::after, .comment-button:hover::after, .repost-button:hover::after, .bookmark-button:hover::after {
        width: 100%;
        height: 100%;
    }
    
    .liked {
        filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);
    }
    
    .bookmarked {
        filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);
    }
</style>
<div class="container" >
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <!-- Профиль -->
            <div class="card mb-4 border-0 shadow-sm" style="margin-top: 20px;">
                <div class="card-body p-4" style="background-color: #dsdsf;">
                    <div class="d-flex align-items-center mb-4">
                        <x-user-avatar :user="$user" :size="112" class="rounded-circle border border-3 border-primary" style="margin-right: 12px !important;" />
                        <div class="ms-4">
                            <h3 class="mb-1" style="font-size: 36px;">{{ $user->name }}</h3>
                            <p class="text-muted mb-2" style="font-size: 22.5px;">{{ $user->email }}</p>
                            @if($user->bio)
                                <p class="mb-3" style="font-size: 22.5px;">{{ $user->bio }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Фильтры -->
                    <div class="profile-filters">
                        <ul class="nav nav-tabs border-0">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#posts">
                                    <i class="fas fa-file-alt me-2"></i>Записи
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#questions">
                                    <i class="fas fa-question-circle me-2"></i>Вопросы
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#comments">
                                    <i class="fas fa-comments me-2"></i>Комментарии
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Контент -->
            <div class="card border-0 shadow-sm" style="margin-top: 20px;">
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="posts">
                            @if($posts->where('type', 'post')->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">У вас пока нет записей</p>
                                </div>
                            @else
                                <div class="posts-container">
                                @foreach($posts->where('type', 'post') as $post)
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
                                                            <span class="badge bg-primary rounded-pill px-3 py-1 me-2">
                                                                Запись
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
                                                                <span class="ms-1 likes-count" style="pointer-events: none;">{{ $post->likes()->count() }}</span>
                                                            </button>
                                                            @else
                                                            <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 me-4">
                                                                <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16">
                                                                <span class="ms-1">{{ $post->likes()->count() }}</span>
                                                            </a>
                                                            @endauth

                                                            <button class="btn btn-link text-dark p-0 me-4 comment-toggle">
                                                                <img src="{{ asset('images/comment.svg') }}" alt="Комментарии" width="20" height="19">
                                                                <span class="ms-1">{{ $post->comments()->count() }}</span>
                                                            </button>

                                                            <button class="btn btn-link text-dark p-0 me-4 repost-button" id="copy-post-link">
                                                                <img src="{{ asset('images/reply.svg') }}" alt="Поделиться" width="20" height="21">
                                                                <span class="ms-1">{{ $post->reposts()->count() }}</span>
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
                                                        <h6 class="mb-3">{{ $post->comments()->count() }} {{ __('posts.comments.' . min($post->comments()->count(), 20)) }}</h6>
                                                        
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
                                                                                        <span class="likes-count {{ auth()->check() && $comment->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $comment->likes()->count() }}</span>
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
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                
                                                                @if($post->comments()->count() > 5)
                                                                    <div class="text-center mt-3">
                                                                        <button class="btn btn-link text-primary load-more-comments" data-post-id="{{ $post->id }}">
                                                                            Показать еще {{ $post->comments()->count() - 5 }} {{ trans_choice('posts.comments', $post->comments()->count() - 5) }}
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="questions">
                            @if($posts->where('type', 'question')->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">У вас пока нет вопросов</p>
                                </div>
                            @else
                                <div class="posts-container">
                                @foreach($posts->where('type', 'question') as $post)
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
                                                            <span class="badge bg-success rounded-pill px-3 py-1 me-2">
                                                                Вопрос
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
                                                                <span class="ms-1 likes-count" style="pointer-events: none;">{{ $post->likes()->count() }}</span>
                                                            </button>
                                                            @else
                                                            <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 me-4">
                                                                <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16">
                                                                <span class="ms-1">{{ $post->likes()->count() }}</span>
                                                            </a>
                                                            @endauth

                                                            <button class="btn btn-link text-dark p-0 me-4 comment-toggle">
                                                                <img src="{{ asset('images/comment.svg') }}" alt="Комментарии" width="20" height="19">
                                                                <span class="ms-1">{{ $post->comments()->count() }}</span>
                                                            </button>

                                                            <button class="btn btn-link text-dark p-0 me-4 repost-button" id="copy-post-link">
                                                                <img src="{{ asset('images/reply.svg') }}" alt="Поделиться" width="20" height="21">
                                                                <span class="ms-1">{{ $post->reposts()->count() }}</span>
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
                                                        <h6 class="mb-3">{{ $post->comments()->count() }} {{ __('posts.comments.' . min($post->comments()->count(), 20)) }}</h6>
                                                        
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
                                                                                        <span class="likes-count {{ auth()->check() && $comment->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $comment->likes()->count() }}</span>
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
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                
                                                                @if($post->comments()->count() > 5)
                                                                    <div class="text-center mt-3">
                                                                        <button class="btn btn-link text-primary load-more-comments" data-post-id="{{ $post->id }}">
                                                                            Показать еще {{ $post->comments()->count() - 5 }} {{ trans_choice('posts.comments', $post->comments()->count() - 5) }}
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="comments">
                            @if($comments->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">У вас пока нет комментариев</p>
                                </div>
                            @else
                                @foreach($comments as $comment)
                                    <div class="post-item mb-4">
                                        <p class="post-content mb-3">{{ $comment->content }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                К посту: <a href="{{ route('posts.show', $comment->post) }}" class="text-decoration-none">{{ $comment->post->title }}</a>
                                                • {{ $comment->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                                {{ $comments->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Правая боковая панель со статистикой -->
        <x-right-sidebar :popularTags="[]" :topUsers="[]" :recentAnswers="[]" :isTagsPage="false" :isHomePage="false" :userStats="$stats" />
    </div>
</div>

<style>
.profile-filters {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
}

.profile-filters .nav-tabs {
    border-bottom: none;
    display: flex;
}

.profile-filters .nav-link {
    color: #6c757d;
    border: none;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
    font-size: 14px;
    white-space: nowrap;
}

.profile-filters .nav-link:hover {
    color: #1682FD;
}

.profile-filters .nav-link.active {
    color: #1682FD;
    border: none;
    border-bottom: 2px solid #1682FD;
    background-color: transparent;
}

.post-item {
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #eee;
}

.post-item:last-child {
    border-bottom: none;
}

.post-title {
    font-size: 18px;
    font-weight: 500;
}

.post-content {
    font-size: 14px;
    line-height: 1.5;
}

.badge {
    font-weight: 500;
}

.bg-light {
    background-color: #f8f9fa !important;
}

/* Стили для статистики */
.right-sidebar .badge {
    background: none !important;
    color: #272727 !important;
    padding: 0;
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Обработка кнопок комментариев
        const commentToggles = document.querySelectorAll('.comment-toggle');
        commentToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const postId = this.closest('.post-card').querySelector('.like-button').dataset.postId;
                const commentsContainer = document.getElementById(`comments-container-${postId}`);
                
                if (commentsContainer.style.display === 'block') {
                    commentsContainer.style.display = 'none';
                } else {
                    commentsContainer.style.display = 'block';
                }
            });
        });
        
        // Обработка кнопок ответа на комментарии
        const replyButtons = document.querySelectorAll('.reply-button');
        replyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                const replyForm = document.getElementById(`reply-form-${commentId}`);
                
                if (replyForm.style.display === 'block') {
                    replyForm.style.display = 'none';
                } else {
                    replyForm.style.display = 'block';
                }
            });
        });
        
        // Обработка кнопок лайков
        const likeButtons = document.querySelectorAll('.like-button');
        likeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const commentId = this.dataset.commentId;
                
                if (postId) {
                    // Лайк поста
                    fetch(`/posts/${postId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const likeImg = this.querySelector('img');
                            const likeCount = this.querySelector('.likes-count');
                            
                            if (data.liked) {
                                likeImg.classList.add('liked');
                                likeCount.classList.add('liked');
                            } else {
                                likeImg.classList.remove('liked');
                                likeCount.classList.remove('liked');
                            }
                            
                            likeCount.textContent = data.likes_count;
                        }
                    });
                } else if (commentId) {
                    // Лайк комментария
                    fetch(`/comments/${commentId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const likeImg = this.querySelector('img');
                            const likeCount = this.querySelector('.likes-count');
                            
                            if (data.liked) {
                                likeImg.classList.add('liked');
                                likeCount.classList.add('liked');
                            } else {
                                likeImg.classList.remove('liked');
                                likeCount.classList.remove('liked');
                            }
                            
                            likeCount.textContent = data.likes_count;
                        }
                    });
                }
            });
        });
        
        // Обработка кнопок закладок
        const bookmarkButtons = document.querySelectorAll('.bookmark-button');
        bookmarkButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const form = this.closest('form');
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const bookmarkImg = this.querySelector('img');
                        
                        if (data.bookmarked) {
                            bookmarkImg.classList.add('bookmarked');
                            this.classList.add('active');
                        } else {
                            bookmarkImg.classList.remove('bookmarked');
                            this.classList.remove('active');
                        }
                    }
                });
            });
        });
        
        // Обработка кнопок "Показать еще комментарии"
        const loadMoreCommentsButtons = document.querySelectorAll('.load-more-comments');
        loadMoreCommentsButtons.forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.postId;
                
                fetch(`/posts/${postId}/comments`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const commentsList = this.closest('.comments-list');
                        const commentsContainer = document.getElementById(`comments-container-${postId}`);
                        
                        // Добавляем новые комментарии
                        data.comments.forEach(comment => {
                            const commentElement = document.createElement('div');
                            commentElement.className = 'comment';
                            commentElement.id = `comment-${comment.id}`;
                            commentElement.innerHTML = `
                                <div class="d-flex">
                                    <a href="/users/${comment.user.id}" class="text-decoration-none me-2">
                                        <img src="${comment.user.avatar_url}" alt="${comment.user.name}" class="rounded-circle" width="32" height="32">
                                    </a>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center">
                                            <a href="/users/${comment.user.id}" class="text-decoration-none text-dark fw-bold me-2">${comment.user.name}</a>
                                            <small class="text-muted">${comment.created_at}</small>
                                        </div>
                                        <div class="comment-content">${comment.content}</div>
                                        
                                        <div class="d-flex align-items-center mt-2">
                                            <div class="d-flex align-items-center me-3 like-button" data-comment-id="${comment.id}">
                                                <img src="/images/like.svg" alt="Лайк" width="16" height="14" class="me-1 ${comment.liked ? 'liked' : ''}">
                                                <span class="likes-count ${comment.liked ? 'liked' : ''}">${comment.likes_count}</span>
                                            </div>
                                            
                                            <button class="btn btn-link text-dark p-0 ms-2 reply-button" data-comment-id="${comment.id}">
                                                Ответить
                                            </button>
                                            
                                            <button class="btn btn-link text-dark p-0 ms-2" data-bs-toggle="modal" data-bs-target="#reportCommentModal${comment.id}">
                                                Пожаловаться
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            commentsList.appendChild(commentElement);
                        });
                        
                        // Удаляем кнопку "Показать еще"
                        this.remove();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection 