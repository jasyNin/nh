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
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
</style>
<div class="container" style="margin-top: 60px;">
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
                
                @if(isset($error))
                    <div class="alert alert-error">
                        {{ $error }}
                    </div>
                @endif
                
                <div class="card-body">
                    @if($posts->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/home.svg') }}" alt="Постов пока нет" width="48" height="48" class="mb-3">
                            <h5 class="fw-light mb-3">Постов пока нет</h5>
                            <p class="text-muted mb-4">Создайте свой первый пост, чтобы начать</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
                                Создать пост
                            </a>
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
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                                <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            @if(auth()->check() && auth()->id() === $post->user_id)
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

                                            <!-- Статистика -->
                                            <div class="d-flex align-items-center text-muted">
                                                <div class="d-flex align-items-center me-4 like-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1">
                                                    <span class="likes-count" style="pointer-events: none;">{{ $post->likes_count }}</span>
                                                </div>
                                                <div class="d-flex align-items-center me-4 comment-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/comment.svg') }}" alt="Комментарии" width="20" height="19" class="me-1">
                                                    <span class="comments-count">{{ $post->comments_count }}</span>
                                                </div>
                                                <div class="d-flex align-items-center me-4 repost-button">
                                                    <img src="{{ asset('images/reply.svg') }}" alt="Поделиться" width="20" height="21" class="me-1">
                                                    <span class="reposts-count">{{ $post->reposts_count }}</span>
                                                </div>
                                                <div class="ms-auto d-flex align-items-center bookmark-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/bookmark-mini.svg') }}" alt="Закладка" width="20" height="20" class="me-1">
                                                </div>
                                            </div>

                                            <!-- Остальной код для отображения комментариев -->
                                            <div id="comments-container-{{ $post->id }}" class="comments-container mt-3 border-top pt-3" style="display: none;">
                                                <!-- Отображение ответов в контейнере комментариев -->
                                                @if($post->type === 'question' && $post->answers->isNotEmpty())
                                                    <div class="answers-section mb-4">
                                                        <h6 class="small fw-bold mb-3">{{ $post->answers_count > 1 ? 'Ответы на вопрос:' : 'Ответ на вопрос:' }}</h6>
                                                        <div class="answers-list">
                                                            @foreach($post->answers->take(2) as $answer)
                                                                <div class="answer-item d-flex align-items-start mb-2 pb-2 border-bottom">
                                                                    <div class="flex-shrink-0 me-2">
                                                                        <a href="{{ route('users.show', $answer->user) }}">
                                                                            <x-user-avatar :user="$answer->user" :size="40" />
                                                                        </a>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex align-items-center mb-1">
                                                                            <a href="{{ route('users.show', $answer->user) }}" class="text-decoration-none text-dark fw-bold me-2">{{ $answer->user->name }}</a>
                                                                            <small class="text-muted">{{ $answer->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                        <div class="answer-content text-muted small">
                                                                            {{ Str::limit($answer->content, 150) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            
                                                            @if($post->answers_count > 2)
                                                                <div class="text-center mt-2">
                                                                    <a href="{{ route('posts.show', $post) }}" class="text-decoration-none small">Показать все ответы ({{ $post->answers_count }})</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <div class="comments-list">
                                                    <h6 class="small fw-bold mb-3">{{ $post->comments_count }} {{ trans_choice('комментариев|комментарий|комментария', $post->comments_count) }}</h6>
                                                    
                                                    @if($post->comments->count() > 0)
                                                        @foreach($post->comments->take(3) as $comment)
                                                            <div class="comment mb-3" id="comment-{{ $comment->id }}">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <a href="{{ route('users.show', $comment->user) }}">
                                                                            <x-user-avatar :user="$comment->user" :size="40" />
                                                                        </a>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div class="d-flex align-items-center">
                                                                                <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none fw-bold me-2">{{ $comment->user->name }}</a>
                                                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                                            </div>
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                                                        <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                                                    </svg>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    @if(auth()->check() && auth()->id() === $comment->user_id)
                                                                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">Редактировать</a></li>
                                                                                        <li>
                                                                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Вы уверены?');">
                                                                                                @csrf
                                                                                                @method('DELETE')
                                                                                                <button type="submit" class="dropdown-item text-danger">Удалить</button>
                                                                                            </form>
                                                                                        </li>
                                                                                    @else
                                                                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportCommentModal{{ $comment->id }}">Пожаловаться</a></li>
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="comment-content mt-1 mb-2">
                                                                            {{ $comment->content }}
                                                                        </div>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="d-flex align-items-center me-3 like-button" data-comment-id="{{ $comment->id }}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart{{ $comment->likedBy(auth()->user()) ? '-fill text-danger' : '' }} me-1" viewBox="0 0 16 16">
                                                                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                                                </svg>
                                                                                <span class="likes-count" style="pointer-events: none;" {{ $comment->likedBy(auth()->user()) ? 'class=active' : '' }}>{{ $comment->likes_count > 0 ? $comment->likes_count : '' }}</span>
                                                                            </div>
                                                                            <div class="d-flex align-items-center me-3 reply-button" data-comment-id="{{ $comment->id }}">
                                                                                <a href="#" class="text-decoration-none text-muted small">Ответить</a>
                                                                            </div>
                                                                            @if($comment->replies->count() > 0)
                                                                            <div class="d-flex align-items-center">
                                                                                <a href="#" class="text-decoration-none replies-toggle" data-comment-id="{{ $comment->id }}">
                                                                                    {{ $comment->replies->count() }} {{ trans_choice('ответ|ответа|ответов', $comment->replies->count()) }}
                                                                                </a>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                        
                                                                        <!-- Форма для ответа на комментарий -->
                                                                        <div class="reply-form mt-3" style="display: none;" id="reply-form-{{ $post->id }}-{{ $comment->id }}">
                                                                            <form action="{{ route('comments.replies.store', $comment) }}" method="POST" class="reply-form-inner">
                                                                                @csrf
                                                                                <div class="input-group">
                                                                                    <textarea name="content" class="form-control comment-textarea" rows="1" placeholder="Ответить..."></textarea>
                                                                                    <button type="submit" class="btn btn-primary comment-submit-btn">
                                                                                        Ответить
                                                                                    </button>
                                                                                </div>
                                                                                <div class="d-flex justify-content-end mt-2">
                                                                                    <button type="button" class="btn btn-link text-muted small p-0 cancel-reply" data-post-id="{{ $post->id }}" data-comment-id="{{ $comment->id }}">Отмена</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                        
                                                                        <!-- Ответы на комментарий -->
                                                                        @if($comment->replies->count() > 0)
                                                                            <div class="replies mt-3" style="display: none;" id="replies-{{ $post->id }}-{{ $comment->id }}">
                                                                                @foreach($comment->replies as $reply)
                                                                                    <div class="reply d-flex mt-2">
                                                                                        <div class="flex-shrink-0 me-2">
                                                                                            <a href="{{ route('users.show', $reply->user) }}">
                                                                                                <x-user-avatar :user="$reply->user" :size="40" />
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class="flex-grow-1">
                                                                                            <div class="d-flex justify-content-between">
                                                                                                <div>
                                                                                                    <a href="{{ route('users.show', $reply->user) }}" class="text-decoration-none fw-bold">{{ $reply->user->name }}</a>
                                                                                                    <small class="text-muted ms-2">{{ $reply->created_at->diffForHumans() }}</small>
                                                                                                </div>
                                                                                                <div class="dropdown">
                                                                                                    <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                                                                            <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                                                                        </svg>
                                                                                                    </button>
                                                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                                                        @if(auth()->check() && auth()->id() === $reply->user_id)
                                                                                                            <li>
                                                                                                                <form action="{{ route('comments.destroy', $reply) }}" method="POST" onsubmit="return confirm('Вы уверены?');">
                                                                                                                    @csrf
                                                                                                                    @method('DELETE')
                                                                                                                    <button type="submit" class="dropdown-item text-danger">Удалить</button>
                                                                                                                </form>
                                                                                                            </li>
                                                                                                        @else
                                                                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportCommentModal{{ $reply->id }}">Пожаловаться</a></li>
                                                                                                        @endif
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="reply-content small">
                                                                                                {{ $reply->content }}
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mt-1">
                                                                                                <div class="d-flex align-items-center me-3 like-button" data-reply-id="{{ $reply->id }}">
                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-heart{{ $reply->likedBy(auth()->user()) ? '-fill text-danger' : '' }} me-1" viewBox="0 0 16 16">
                                                                                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                                                                    </svg>
                                                                                                    <span class="likes-count" style="pointer-events: none;" {{ $reply->likedBy(auth()->user()) ? 'class=active' : '' }}>{{ $reply->likes_count > 0 ? $reply->likes_count : '' }}</span>
                                                                                                </div>
                                                                                                <div class="d-flex align-items-center reply-to-reply-button" data-post-id="{{ $post->id }}" data-comment-id="{{ $comment->id }}" data-reply-id="{{ $reply->id }}">
                                                                                                    <a href="#" class="text-decoration-none text-muted small">Ответить</a>
                                                                                                </div>
                                                                                            </div>
                                                                                            
                                                                                            <!-- Форма для ответа на ответ -->
                                                                                            <div class="reply-to-reply-form mt-2" style="display: none;" id="reply-to-reply-form-{{ $post->id }}-{{ $comment->id }}-{{ $reply->id }}">
                                                                                                <form action="{{ route('comments.replies.store', $comment) }}" method="POST" class="reply-to-reply-form-inner">
                                                                                                    @csrf
                                                                                                    <div class="input-group">
                                                                                                        <textarea name="content" class="form-control comment-textarea" rows="1" placeholder="Ответить..."></textarea>
                                                                                                        <button type="submit" class="btn btn-primary comment-submit-btn">
                                                                                                            Ответить
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class="d-flex justify-content-end mt-2">
                                                                                                        <button type="button" class="btn btn-link text-muted small p-0 cancel-reply-to-reply" data-post-id="{{ $post->id }}" data-comment-id="{{ $comment->id }}" data-reply-id="{{ $reply->id }}">Отмена</button>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        
                                                        @if($post->comments->count() > 3)
                                                            <div class="text-center mb-3">
                                                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">Смотреть все комментарии ({{ $post->comments_count }})</a>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="text-center py-3">
                                                            <p class="text-muted mb-0">Будьте первым, кто оставит комментарий!</p>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                @auth
                                                    <div class="comment-form-container mt-3">
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
                                                    <div class="text-center py-3">
                                                        <p class="mb-0">Чтобы оставить комментарий, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a></p>
                                                    </div>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Правая колонка с боковыми панелями --> 
        <x-right-sidebar :popularTags="$popularTags" :topUsers="$topUsers" :isHomePage="true" />
    </div>
</div>

@push('styles')
<!-- Стили для главной страницы перенесены в общий файл CSS app.css -->
@endpush

@push('scripts')
<!-- Скрипты для взаимодействия с постами -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Функция для обработки клика на кнопку лайка
    function handleLikeButtonClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const commentId = this.dataset.commentId;
        const postId = this.dataset.postId;
        const url = commentId ? `/comments/${commentId}/like` : `/posts/${postId}/like`;
        const likesCount = this.querySelector('.likes-count');
        const heartIcon = this.querySelector('svg');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Обновляем счетчик лайков
            if (likesCount) {
                likesCount.textContent = data.likes_count > 0 ? data.likes_count : '';
                
                // Добавляем/удаляем класс active для счетчика
                if (data.liked) {
                    likesCount.classList.add('active');
                } else {
                    likesCount.classList.remove('active');
                }
            }
            
            // Обновляем SVG иконку сердечка
            if (heartIcon) {
                if (data.liked) {
                    heartIcon.classList.remove('bi-heart');
                    heartIcon.classList.add('bi-heart-fill', 'text-danger');
                } else {
                    heartIcon.classList.remove('bi-heart-fill', 'text-danger');
                    heartIcon.classList.add('bi-heart');
                }
            }
            
            // Обновляем стили самих кнопок
            if (data.liked) {
                this.classList.add('active');
            } else {
                this.classList.remove('active');
            }
        });
    }

    // Применяем обработчик ко всем кнопкам лайка
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', handleLikeButtonClick);
    });
    
    // Обработка клика на счетчик ответов
    document.querySelectorAll('.replies-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const commentId = this.dataset.commentId;
            const comment = this.closest('.comment');
            const repliesContainer = comment.querySelector('.replies');
            
            if (repliesContainer) {
                // Проверяем, отображаются ли ответы сейчас
                const isRepliesVisible = repliesContainer.style.display !== 'none';
                
                // Переключаем видимость ответов
                if (isRepliesVisible) {
                    repliesContainer.style.display = 'none';
                    this.classList.remove('active');
                } else {
                    repliesContainer.style.display = 'block';
                    this.classList.add('active');
                }
            }
        });
    });
    
    // Комментарии - показать/скрыть комментарии при клике на кнопку комментариев
    document.querySelectorAll('.comment-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.dataset.postId;
            const commentsContainer = document.getElementById(`comments-container-${postId}`);
            
            // Проверяем, отображаются ли комментарии сейчас
            const isCommentsVisible = commentsContainer.style.display !== 'none';
            
            // Добавляем класс active для кнопки комментария
            if (!isCommentsVisible) {
                this.classList.add('active');
                const img = this.querySelector('img');
                if (img) {
                    img.classList.add('active');
                }
                
                // Добавляем класс active для счетчика комментариев
                const commentsCount = this.querySelector('.comments-count');
                if (commentsCount) {
                    commentsCount.classList.add('active');
                }
                
                // Показываем комментарии
                commentsContainer.style.display = 'block';
                
                // Если пользователь кликнул на область комментариев, останавливаем всплытие события
                commentsContainer.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
                
                // Обрабатываем отправку комментария без перезагрузки страницы
                const commentForm = commentsContainer.querySelector('.comment-form');
                if (commentForm) {
                    commentForm.addEventListener('submit', function(event) {
                        event.preventDefault();
                        
                        const formData = new FormData(this);
                        fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Увеличиваем счетчик комментариев
                                const commentsCount = button.querySelector('.comments-count');
                                if (commentsCount) {
                                    commentsCount.textContent = parseInt(commentsCount.textContent) + 1;
                                }
                                
                                // Очищаем форму
                                commentForm.reset();
                                
                                // Добавляем новый комментарий в список
                                const commentsList = commentsContainer.querySelector('.comments-list');
                                if (commentsList) {
                                    const newComment = document.createElement('div');
                                    newComment.className = 'comment mb-3';
                                    newComment.innerHTML = `
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <a href="${data.user_url}">
                                                    <img src="${data.user_avatar}" alt="${data.user_name}" class="rounded-circle" width="40" height="40">
                                                </a>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <a href="${data.user_url}" class="text-decoration-none fw-bold me-2">${data.user_name}</a>
                                                        <small class="text-muted">только что</small>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#" data-comment-id="${data.comment_id}">Пожаловаться</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="comment-content mt-1 mb-2">
                                                    ${data.content}
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex align-items-center me-3 like-button" data-comment-id="${data.comment_id}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart me-1" viewBox="0 0 16 16">
                                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                        </svg>
                                                        <span class="likes-count" style="pointer-events: none;"></span>
                                                    </div>
                                                    <div class="d-flex align-items-center reply-button" data-comment-id="${data.comment_id}">
                                                        <a href="#" class="text-decoration-none text-muted small">Ответить</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    
                                    // Добавляем комментарий в начало списка
                                    const commentsHeader = commentsList.querySelector('h6');
                                    if (commentsHeader) {
                                        commentsList.insertBefore(newComment, commentsHeader.nextSibling);
                                    } else {
                                        commentsList.appendChild(newComment);
                                    }
                                    
                                    // Добавляем обработчик клика для нового комментария
                                    const newLikeButton = newComment.querySelector('.like-button');
                                    if (newLikeButton) {
                                        newLikeButton.addEventListener('click', handleLikeButtonClick);
                                    }
                                    
                                    // Добавляем обработчик для кнопки ответа
                                    const newReplyButton = newComment.querySelector('.reply-button');
                                    if (newReplyButton) {
                                        newReplyButton.addEventListener('click', handleReplyButtonClick);
                                    }
                                }
                            }
                        });
                    });
                }
            } else {
                // Скрываем комментарии и убираем активные классы
                this.classList.remove('active');
                const img = this.querySelector('img');
                if (img) {
                    img.classList.remove('active');
                }
                
                const commentsCount = this.querySelector('.comments-count');
                if (commentsCount) {
                    commentsCount.classList.remove('active');
                }
                
                commentsContainer.style.display = 'none';
            }
        });
    });
    
    // Обработка кликов на кнопку "Ответить" в комментариях
    function handleReplyButtonClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const commentId = this.dataset.commentId;
        const postId = this.closest('.post-card').querySelector('.like-button').dataset.postId;
        const replyFormId = `reply-form-${postId}-${commentId}`;
        
        // Получаем имя пользователя, на комментарий которого отвечаем
        const commentElement = this.closest('.comment');
        const userName = commentElement.querySelector('.text-decoration-none.fw-bold').textContent.trim();
        
        // Находим форму ответа
        let replyForm = document.getElementById(replyFormId);
        
        if (replyForm) {
            // Если форма существует, переключаем её видимость
            if (replyForm.style.display === 'none') {
                replyForm.style.display = 'block';
                const textarea = replyForm.querySelector('textarea');
                
                // Добавляем префикс @username, если он еще не добавлен
                if (!textarea.value.includes(`@${userName}`)) {
                    textarea.value = `@${userName} `;
                }
                
                textarea.focus();
                // Устанавливаем курсор в конец текста
                textarea.selectionStart = textarea.selectionEnd = textarea.value.length;
            } else {
                replyForm.style.display = 'none';
            }
            return;
        }
        
        // Если форма не найдена, код не продолжается дальше
        console.log('Форма для ответа не найдена:', replyFormId);
    }
    
    // Обработчик для отмены ответа на комментарий
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.dataset.postId;
            const commentId = this.dataset.commentId;
            const replyForm = document.getElementById(`reply-form-${postId}-${commentId}`);
            
            if (replyForm) {
                replyForm.style.display = 'none';
                replyForm.querySelector('textarea').value = '';
            }
        });
    });
    
    // Обработка кликов на кнопку "Ответить" в ответах на комментарии
    function handleReplyToReplyButtonClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const postId = this.dataset.postId;
        const commentId = this.dataset.commentId;
        const replyId = this.dataset.replyId;
        
        // Получаем имя пользователя, на которого отвечаем
        const replyElement = this.closest('.reply');
        const userName = replyElement.querySelector('.text-decoration-none.fw-bold').textContent.trim();
        
        // Находим форму ответа
        const replyToReplyForm = document.getElementById(`reply-to-reply-form-${postId}-${commentId}-${replyId}`);
        
        if (!replyToReplyForm) return;
        
        // Показываем форму и фокусируемся на поле ввода
        replyToReplyForm.style.display = 'block';
        const textarea = replyToReplyForm.querySelector('textarea');
        
        // Добавляем префикс @username, если он еще не добавлен
        if (!textarea.value.includes(`@${userName}`)) {
            textarea.value = `@${userName} `;
        }
        
        textarea.focus();
        // Устанавливаем курсор в конец текста
        textarea.selectionStart = textarea.selectionEnd = textarea.value.length;
        
        // Обрабатываем отправку формы
        const form = replyToReplyForm.querySelector('form');
        
        // Проверяем, не добавлен ли уже обработчик
        if (!form.hasSubmitHandler) {
            form.hasSubmitHandler = true;
            
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                
                const formData = new FormData(this);
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Скрываем форму и очищаем её
                        replyToReplyForm.style.display = 'none';
                        form.reset();
                        
                        // Создаем новый ответ и добавляем его в DOM
                        const repliesContainer = replyElement.closest('.replies');
                        if (repliesContainer) {
                            const newReply = document.createElement('div');
                            newReply.className = 'reply d-flex mt-2';
                            newReply.innerHTML = `
                                <div class="flex-shrink-0 me-2">
                                    <a href="${data.user_url}">
                                        <img src="${data.user_avatar}" alt="${data.user_name}" class="rounded-circle" width="40" height="40">
                                    </a>
                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <a href="${data.user_url}" class="text-decoration-none fw-bold">${data.user_name}</a>
                                            <small class="text-muted ms-2">только что</small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                    <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <form action="/comments/${data.comment_id}" method="POST" onsubmit="return confirm('Вы уверены?');">
                                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="dropdown-item text-danger">Удалить</button>
            </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="reply-content small">
                                        ${data.content}
                                    </div>
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="d-flex align-items-center me-3 like-button" data-reply-id="${data.comment_id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-heart me-1" viewBox="0 0 16 16">
                                                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                            </svg>
                                            <span class="likes-count" style="pointer-events: none;">0</span>
                                        </div>
                                        <div class="d-flex align-items-center reply-to-reply-button" data-post-id="${postId}" data-comment-id="${commentId}" data-reply-id="${data.comment_id}">
                                            <a href="#" class="text-decoration-none text-muted small">Ответить</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            // Добавляем новый ответ в контейнер
                            repliesContainer.appendChild(newReply);
                            
                            // Добавляем обработчики для нового ответа
                            const newLikeButton = newReply.querySelector('.like-button');
                            if (newLikeButton) {
                                newLikeButton.addEventListener('click', handleLikeButtonClick);
                            }
                            
                            const newReplyButton = newReply.querySelector('.reply-to-reply-button');
                            if (newReplyButton) {
                                newReplyButton.addEventListener('click', handleReplyToReplyButtonClick);
                            }
                        }
                        
                        // Показываем уведомление
                        const toast = document.createElement('div');
                        toast.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                        toast.style.zIndex = '9999';
                        toast.textContent = 'Ответ отправлен';
                        
                        document.body.appendChild(toast);
                        
                        // Удаляем уведомление через 2 секунды
                        setTimeout(() => {
                            toast.remove();
                        }, 2000);
                    }
                });
            });
        }
    }
    
    // Обработка отправки формы ответа на комментарий
    document.querySelectorAll('.reply-form-inner').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const commentId = this.closest('.comment').id.replace('comment-', '');
            const postId = this.closest('.post-card').querySelector('.like-button').dataset.postId;
            const replyForm = this.closest('.reply-form');
            const repliesContainer = this.closest('.comment').querySelector('.replies');
            let repliesToggle = this.closest('.comment').querySelector('.replies-toggle');
            
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Скрываем форму и очищаем её
                    replyForm.style.display = 'none';
                    this.reset();
                    
                    // Если контейнер с ответами не существует, создаем его
                    if (!repliesContainer) {
                        const newRepliesContainer = document.createElement('div');
                        newRepliesContainer.className = 'replies mt-3';
                        newRepliesContainer.id = `replies-${postId}-${commentId}`;
                        newRepliesContainer.style.display = 'block';
                        
                        // Вставляем контейнер перед формой ответа
                        this.closest('.comment').querySelector('.flex-grow-1').appendChild(newRepliesContainer);
                        
                        // Создаем новый toggle для ответов, если его еще нет
                        if (!repliesToggle) {
                            const toggleContainer = document.createElement('div');
                            toggleContainer.className = 'd-flex align-items-center';
                            toggleContainer.innerHTML = `
                                <a href="#" class="text-decoration-none replies-toggle active" data-comment-id="${commentId}">
                                    1 ответ
                                </a>
                            `;
                            
                            // Находим место для вставки toggle после кнопки "Ответить"
                            const replyButton = this.closest('.comment').querySelector('.reply-button');
                            const replyButtonParent = replyButton.parentNode;
                            replyButtonParent.parentNode.insertBefore(toggleContainer, replyButtonParent.nextSibling);
                            
                            // Назначаем обработчик для нового toggle
                            const newToggle = toggleContainer.querySelector('.replies-toggle');
                            newToggle.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                
                                const commentId = this.dataset.commentId;
                                const comment = this.closest('.comment');
                                const repliesContainer = comment.querySelector('.replies');
                                
                                if (repliesContainer) {
                                    const isRepliesVisible = repliesContainer.style.display !== 'none';
                                    
                                    if (isRepliesVisible) {
                                        repliesContainer.style.display = 'none';
                                        this.classList.remove('active');
                                    } else {
                                        repliesContainer.style.display = 'block';
                                        this.classList.add('active');
                                    }
                                }
                            });
                            
                            repliesToggle = newToggle;
                        } else {
                            // Обновляем счетчик ответов
                            const replyCount = parseInt(repliesToggle.textContent.match(/\d+/)[0]) + 1;
                            repliesToggle.textContent = `${replyCount} ${trans_choice('ответ|ответа|ответов', replyCount)}`;
                            repliesToggle.classList.add('active');
                        }
                    }
                    
                    // Создаем новый ответ и добавляем его в DOM
                    const repliesContainerToUse = repliesContainer || document.getElementById(`replies-${postId}-${commentId}`);
                    if (repliesContainerToUse) {
                        repliesContainerToUse.style.display = 'block';
                        
                        const newReply = document.createElement('div');
                        newReply.className = 'reply d-flex mt-2';
                        newReply.innerHTML = `
                            <div class="flex-shrink-0 me-2">
                                <a href="${data.user_url}">
                                    <img src="${data.user_avatar}" alt="${data.user_name}" class="rounded-circle" width="40" height="40">
                                </a>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="${data.user_url}" class="text-decoration-none fw-bold">${data.user_name}</a>
                                        <small class="text-muted ms-2">только что</small>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <form action="/comments/${data.comment_id}" method="POST" onsubmit="return confirm('Вы уверены?');">
                                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="dropdown-item text-danger">Удалить</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="reply-content small">
                                    ${data.content}
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="d-flex align-items-center me-3 like-button" data-reply-id="${data.comment_id}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-heart me-1" viewBox="0 0 16 16">
                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                        </svg>
                                        <span class="likes-count" style="pointer-events: none;">0</span>
                                    </div>
                                    <div class="d-flex align-items-center reply-to-reply-button" data-post-id="${postId}" data-comment-id="${commentId}" data-reply-id="${data.comment_id}">
                                        <a href="#" class="text-decoration-none text-muted small">Ответить</a>
                                    </div>
                                </div>
                                
                                <!-- Форма для ответа на ответ -->
                                <div class="reply-to-reply-form mt-2" style="display: none;" id="reply-to-reply-form-${postId}-${commentId}-${data.comment_id}">
                                    <form action="/comments/${commentId}/replies" method="POST" class="reply-to-reply-form-inner">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <div class="input-group">
                                            <textarea name="content" class="form-control comment-textarea" rows="1" placeholder="Ответить..."></textarea>
                                            <button type="submit" class="btn btn-primary comment-submit-btn">
                                                Ответить
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-end mt-2">
                                            <button type="button" class="btn btn-link text-muted small p-0 cancel-reply-to-reply" data-post-id="${postId}" data-comment-id="${commentId}" data-reply-id="${data.comment_id}">Отмена</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        `;
                        
                        // Добавляем новый ответ в контейнер
                        repliesContainerToUse.appendChild(newReply);
                        
                        // Добавляем обработчики для нового ответа
                        const newLikeButton = newReply.querySelector('.like-button');
                        if (newLikeButton) {
                            newLikeButton.addEventListener('click', handleLikeButtonClick);
                        }
                        
                        const newReplyButton = newReply.querySelector('.reply-to-reply-button');
                        if (newReplyButton) {
                            newReplyButton.addEventListener('click', handleReplyToReplyButtonClick);
                        }
                        
                        const newCancelButton = newReply.querySelector('.cancel-reply-to-reply');
                        if (newCancelButton) {
                            newCancelButton.addEventListener('click', handleCancelReplyToReply);
                        }
                    }
                    
                    // Показываем уведомление
                    const toast = document.createElement('div');
                    toast.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                    toast.style.zIndex = '9999';
                    toast.textContent = 'Ответ отправлен';
                    
                    document.body.appendChild(toast);
                    
                    // Удаляем уведомление через 2 секунды
                    setTimeout(() => {
                        toast.remove();
                    }, 2000);
                }
            });
        });
    });
    
    // Обработка кликов на кнопку "Отмена" в ответах на ответы
    function handleCancelReplyToReply(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const postId = this.dataset.postId;
        const commentId = this.dataset.commentId;
        const replyId = this.dataset.replyId;
        
        // Находим форму ответа и скрываем её
        const replyToReplyForm = document.getElementById(`reply-to-reply-form-${postId}-${commentId}-${replyId}`);
        
        if (replyToReplyForm) {
            replyToReplyForm.style.display = 'none';
            replyToReplyForm.querySelector('form').reset();
        }
    }
    
    // Добавляем обработчики для всех кнопок "Ответить" в комментариях
    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', handleReplyButtonClick);
    });
    
    // Добавляем обработчики для всех кнопок "Ответить" в ответах
    document.querySelectorAll('.reply-to-reply-button').forEach(button => {
        button.addEventListener('click', handleReplyToReplyButtonClick);
    });
    
    // Добавляем обработчики для всех кнопок "Отмена" в ответах на ответы
    document.querySelectorAll('.cancel-reply-to-reply').forEach(button => {
        button.addEventListener('click', handleCancelReplyToReply);
    });
    
    // Предотвращаем переход при клике на пост, если клик был по комментариям
    document.querySelectorAll('.post-content').forEach(content => {
        content.addEventListener('click', function(e) {
            if (e.target.closest('.comments-container')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    
    // Репосты - копирование ссылки
    document.querySelectorAll('.repost-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.closest('.post-card').querySelector('.like-button').dataset.postId;
            const postUrl = `/posts/${postId}`;
            
            navigator.clipboard.writeText(window.location.origin + postUrl).then(() => {
                // Добавляем класс active для визуального эффекта
                this.classList.add('active');
                const img = this.querySelector('img');
                if (img) {
                    img.classList.add('active');
                }
                const repostsCount = this.querySelector('.reposts-count');
                if (repostsCount) {
                    repostsCount.classList.add('active');
                }
                
                // Создаем и показываем сообщение
                const toast = document.createElement('div');
                toast.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                toast.style.zIndex = '9999';
                toast.textContent = 'Ссылка скопирована в буфер обмена';
                
                document.body.appendChild(toast);
                
                // Удаляем сообщение и класс active через 2 секунды
                setTimeout(() => {
                    toast.remove();
                    this.classList.remove('active');
                    if (img) {
                        img.classList.remove('active');
                    }
                    if (repostsCount) {
                        repostsCount.classList.remove('active');
                    }
                }, 2000);
                
                // Увеличиваем счетчик репостов
                if (repostsCount) {
                    repostsCount.textContent = parseInt(repostsCount.textContent) + 1;
                }
            }).catch(err => {
                console.error('Не удалось скопировать: ', err);
            });
        });
    });
    
    // Закладки
    document.querySelectorAll('.bookmark-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.dataset.postId;
            const bookmarkImg = this.querySelector('img');
            
            fetch(`/posts/${postId}/bookmark`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Обновляем состояние на всех карточках с этим постом
                document.querySelectorAll(`.bookmark-button[data-post-id="${postId}"]`).forEach(btn => {
                    if (data.bookmarked) {
                        btn.classList.add('active');
                        const img = btn.querySelector('img');
                        if (img) {
                            img.classList.add('bookmarked');
                        }
                    } else {
                        btn.classList.remove('active');
                        const img = btn.querySelector('img');
                        if (img) {
                            img.classList.remove('bookmarked');
                        }
                    }
                });
            });
        });
    });
});
</script>
@endpush

@endsection 