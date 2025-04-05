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
        transition: all 0.2s ease;
    }
    .like-button:hover, .comment-button:hover, .repost-button:hover, .bookmark-button:hover {
        opacity: 0.8;
    }
    .like-button.active img, .like-button img.liked {
        filter: invert(32%) sepia(98%) saturate(1746%) hue-rotate(314deg) brightness(87%) contrast(87%);
    }
    .bookmark-button.active img, .bookmark-button img.bookmarked {
        filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(118%) contrast(119%);
    }
    .repost-button.active img, .repost-button img.active {
        filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(118%) contrast(119%);
    }
    .comment-button.active img, .comment-button img.active {
        filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(118%) contrast(119%);
    }
    .likes-count.active, .comments-count.active, .reposts-count.active {
        color: #1682FD;
    }
    .likes-count.liked {
        color: #E65C77;
    }
    .replies-container {
        margin-left: 20px;
        margin-top: 10px;
        display: none;
    }
    .reply {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .reply:last-child {
        border-bottom: none;
    }
    .reply-form-container {
        margin-top: 10px;
        margin-left: 20px;
        display: none;
    }
    .reply-textarea {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        resize: none;
    }
    .reply-submit-btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        background-color: #1682FD;
        color: white;
        font-size: 14px;
        padding: 6px 16px;
    }
    .toast-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        padding: 10px 20px;
        border-radius: 4px;
        background-color: #28a745;
        color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        display: none;
    }
    .reply-to-reply-form-container {
        margin-top: 10px;
        margin-left: 20px;
        display: none;
    }
    .reply-to-reply-textarea {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        resize: none;
    }
    .reply-to-reply-submit-btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        background-color: #1682FD;
        color: white;
        font-size: 14px;
        padding: 6px 16px;
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

                                            <!-- Статистика и кнопки взаимодействия -->
                                            <div class="d-flex align-items-center text-muted">
                                                <div class="d-flex align-items-center me-4 like-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1 {{ $post->likedBy(auth()->user()) ? 'liked' : '' }}">
                                                    <span class="likes-count {{ $post->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $post->likes_count }}</span>
                                                </div>
                                                <div class="d-flex align-items-center me-4 comment-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/comment.svg') }}" alt="Комментарии" width="20" height="19" class="me-1">
                                                    <span class="comments-count">{{ $post->comments_count }}</span>
                                                </div>
                                                <div class="d-flex align-items-center me-4 repost-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/reply.svg') }}" alt="Поделиться" width="20" height="21" class="me-1">
                                                    <span class="reposts-count">{{ $post->reposts_count }}</span>
                                                </div>
                                                <div class="ms-auto d-flex align-items-center bookmark-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/bookmark-mini.svg') }}" alt="Закладка" width="20" height="20" class="me-1 {{ $post->isBookmarkedBy(auth()->user()) ? 'bookmarked' : '' }}">
                                                </div>
                                            </div>
                                            
                                            <!-- Комментарии -->
                                            <div class="comments-container" id="comments-container-{{ $post->id }}">
                                                <h6 class="mb-3">{{ $post->comments_count }} {{ __('posts.comments.' . min($post->comments_count, 20)) }}</h6>
                                                
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
                                                                        @foreach($comment->replies->take(3) as $reply)
                                                                            <div class="reply" id="reply-{{ $reply->id }}">
                                                                                <div class="d-flex">
                                                                                    <a href="{{ route('users.show', $reply->user) }}" class="text-decoration-none me-2">
                                                                                        <x-user-avatar :user="$reply->user" :size="24" />
                                                                                    </a>
                                                                                    <div class="flex-grow-1">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <a href="{{ route('users.show', $reply->user) }}" class="text-decoration-none text-dark fw-bold me-2">{{ $reply->user->name }}</a>
                                                                                            <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                                                        </div>
                                                                                        <div class="comment-content">{{ $reply->content }}</div>
                                                                                        
                                                                                        <div class="d-flex align-items-center mt-2">
                                                                                            <div class="d-flex align-items-center me-3 like-button" data-reply-id="{{ $reply->id }}">
                                                                                                <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="16" height="14" class="me-1 {{ auth()->check() && $reply->likedBy(auth()->user()) ? 'liked' : '' }}">
                                                                                                <span class="likes-count {{ auth()->check() && $reply->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $reply->likes_count }}</span>
                                                                                            </div>
                                                                                            
                                                                                            <button class="btn btn-link text-dark p-0 ms-2 reply-to-reply-button" data-reply-id="{{ $reply->id }}">
                                                                                                Ответить
                                                                                            </button>
                                                                                        </div>
                                                                                        
                                                                                        <!-- Форма ответа на ответ -->
                                                                                        <div class="reply-to-reply-form-container" id="reply-to-reply-form-{{ $reply->id }}">
                                                                                            <form action="{{ route('replies.replies.store', $reply) }}" method="POST" class="reply-to-reply-form">
                                                                                                @csrf
                                                                                                <div class="input-group">
                                                                                                    <textarea name="content" class="form-control reply-to-reply-textarea" rows="1" placeholder="Ответить..."></textarea>
                                                                                                    <button type="submit" class="btn btn-primary reply-to-reply-submit-btn">
                                                                                                        Отправить
                                                                                                    </button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                        
                                                                        @if($comment->replies_count > 3)
                                                                            <div class="text-center mt-2">
                                                                                <a href="{{ route('posts.show', $post) }}#comment-{{ $comment->id }}" class="text-decoration-none">
                                                                                    Показать все ответы ({{ $comment->replies_count }})
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    
                                                    @if($post->comments_count > 5)
                                                        <div class="text-center mt-3">
                                                            <a href="{{ route('posts.show', $post) }}#comments" class="text-decoration-none">
                                                                Показать все комментарии ({{ $post->comments_count }})
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                
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
                                                        <a href="{{ route('login') }}" class="text-decoration-none">
                                                            Войдите, чтобы оставить комментарий
                                                        </a>
                                                    </div>
                                                @endauth
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
        <x-right-sidebar :popularTags="$popularTags" :topUsers="$topUsers" :recentAnswers="$recentAnswers" :isHomePage="true" />
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Создаем элемент для уведомлений
        const toast = document.createElement('div');
        toast.className = 'toast-message';
        document.body.appendChild(toast);
        
        // Функция для показа уведомлений
        function showToast(message, type = 'success') {
            toast.textContent = message;
            toast.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';
            toast.style.display = 'block';
            
            setTimeout(() => {
                toast.style.display = 'none';
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
                } else {
                    console.error('Не указан id для лайка');
                    return;
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
                        // Обновляем текст счетчика лайков
                        likesCount.textContent = data.likes_count;
                        
                        // Добавляем/удаляем класс active для счетчика
                        if (data.liked) {
                            likesCount.classList.add('liked');
                        } else {
                            likesCount.classList.remove('liked');
                        }
                    }
                    
                    // Добавляем/удаляем класс для изменения цвета иконки
                    if (img) {
                        if (data.liked) {
                            img.classList.add('liked');
                        } else {
                            img.classList.remove('liked');
                        }
                    }
                    
                    // Добавляем/удаляем класс active для кнопки
                    if (data.liked) {
                        this.classList.add('active');
                    } else {
                        this.classList.remove('active');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
                @else
                window.location.href = '{{ route("login") }}';
                @endauth
            });
        });
        
        // Обработка закладок
        document.querySelectorAll('.bookmark-button').forEach(button => {
            button.addEventListener('click', function() {
                @auth
                const postId = this.dataset.postId;
                if (!postId) return;
                
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
                        img.classList.add('bookmarked');
                        this.classList.add('active');
                        showToast('Пост добавлен в закладки');
                    } else {
                        img.classList.remove('bookmarked');
                        this.classList.remove('active');
                        showToast('Пост удален из закладок');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
                @else
                window.location.href = '{{ route("login") }}';
                @endauth
            });
        });
        
        // Обработка комментариев
        document.querySelectorAll('.comment-button').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.postId;
                if (!postId) return;
                
                const commentsContainer = document.getElementById(`comments-container-${postId}`);
                
                if (commentsContainer.style.display === 'none' || !commentsContainer.style.display) {
                    commentsContainer.style.display = 'block';
                    this.classList.add('active');
                    
                    // Добавляем класс active для изображения
                    const img = this.querySelector('img');
                    if (img) {
                        img.classList.add('active');
                    }
                    
                    // Добавляем класс active для счетчика
                    const span = this.querySelector('span');
                    if (span) {
                        span.classList.add('active');
                    }
                } else {
                    commentsContainer.style.display = 'none';
                    this.classList.remove('active');
                    
                    // Удаляем класс active для изображения
                    const img = this.querySelector('img');
                    if (img) {
                        img.classList.remove('active');
                    }
                    
                    // Удаляем класс active для счетчика
                    const span = this.querySelector('span');
                    if (span) {
                        span.classList.remove('active');
                    }
                }
            });
        });
        
        // Обработка репостов (копирование ссылки)
        document.querySelectorAll('.repost-button').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.postId;
                if (!postId) return;
                
                // Получаем URL поста
                const postUrl = `${window.location.origin}/posts/${postId}`;
                
                // Копируем ссылку в буфер обмена
                navigator.clipboard.writeText(postUrl).then(() => {
                    // Добавляем класс active для кнопки и элементов
                    this.classList.add('active');
                    
                    // Добавляем класс active для изображения
                    const img = this.querySelector('img');
                    if (img) {
                        img.classList.add('active');
                    }
                    
                    // Добавляем класс active для счетчика
                    const span = this.querySelector('span');
                    if (span) {
                        span.classList.add('active');
                    }
                    
                    // Показываем уведомление
                    showToast('Ссылка скопирована в буфер обмена');
                    
                    // Удаляем класс active через 2 секунды
                    setTimeout(() => {
                        this.classList.remove('active');
                        if (img) {
                            img.classList.remove('active');
                        }
                        if (span) {
                            span.classList.remove('active');
                        }
                    }, 2000);
                }).catch(err => {
                    console.error('Не удалось скопировать ссылку:', err);
                });
            });
        });
        
        // Обработка переключения ответов
        document.querySelectorAll('.replies-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                const repliesContainer = document.getElementById(`replies-${commentId}`);
                
                if (repliesContainer.style.display === 'none' || !repliesContainer.style.display) {
                    repliesContainer.style.display = 'block';
                    this.classList.add('active');
                } else {
                    repliesContainer.style.display = 'none';
                    this.classList.remove('active');
                }
            });
        });
        
        // Обработка кнопок "Ответить"
        document.querySelectorAll('.reply-button').forEach(button => {
            button.addEventListener('click', function() {
                @auth
                const commentId = this.dataset.commentId;
                const replyForm = document.getElementById(`reply-form-${commentId}`);
                
                if (replyForm.style.display === 'none' || !replyForm.style.display) {
                    replyForm.style.display = 'block';
                    replyForm.querySelector('textarea').focus();
                } else {
                    replyForm.style.display = 'none';
                }
                @else
                window.location.href = '{{ route("login") }}';
                @endauth
            });
        });
        
        // Обработка кнопок "Ответить" на ответы
        document.querySelectorAll('.reply-to-reply-button').forEach(button => {
            button.addEventListener('click', function() {
                @auth
                const replyId = this.dataset.replyId;
                const replyToReplyForm = document.getElementById(`reply-to-reply-form-${replyId}`);
                
                if (replyToReplyForm.style.display === 'none' || !replyToReplyForm.style.display) {
                    replyToReplyForm.style.display = 'block';
                    replyToReplyForm.querySelector('textarea').focus();
                } else {
                    replyToReplyForm.style.display = 'none';
                }
                @else
                window.location.href = '{{ route("login") }}';
                @endauth
            });
        });
        
        // Обработка отправки комментариев
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const postId = this.action.split('/').pop();
                
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
                    // Очищаем поле ввода
                    this.querySelector('textarea').value = '';
                    
                    // Обновляем счетчик комментариев
                    const commentButton = document.querySelector(`.comment-button[data-post-id="${postId}"]`);
                    if (commentButton) {
                        const commentsCount = commentButton.querySelector('.comments-count');
                        if (commentsCount) {
                            commentsCount.textContent = data.comments_count;
                        }
                    }
                    
                    // Добавляем новый комментарий в список
                    const commentsList = document.querySelector(`#comments-container-${postId} .comments-list`);
                    if (commentsList) {
                        const newComment = document.createElement('div');
                        newComment.className = 'comment';
                        newComment.id = `comment-${data.comment_id}`;
                        newComment.innerHTML = `
                            <div class="d-flex">
                                <a href="/users/${data.user_id}" class="text-decoration-none me-2">
                                    <img src="${data.user_avatar}" alt="${data.user_name}" class="rounded-circle" width="32" height="32">
                                </a>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <a href="/users/${data.user_id}" class="text-decoration-none text-dark fw-bold me-2">${data.user_name}</a>
                                        <small class="text-muted">сейчас</small>
                                    </div>
                                    <div class="comment-content">${data.content}</div>
                                    
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="d-flex align-items-center me-3 like-button" data-comment-id="${data.comment_id}">
                                            <img src="/images/like.svg" alt="Лайк" width="16" height="14" class="me-1">
                                            <span class="likes-count">0</span>
                                        </div>
                                        
                                        <div class="replies-toggle" data-comment-id="${data.comment_id}">
                                            0 ответов
                                        </div>
                                        
                                        <button class="btn btn-link text-dark p-0 ms-2 reply-button" data-comment-id="${data.comment_id}">
                                            Ответить
                                        </button>
                                    </div>
                                    
                                    <!-- Форма ответа на комментарий -->
                                    <div class="reply-form-container" id="reply-form-${data.comment_id}" style="display: none;">
                                        <form action="/comments/${data.comment_id}/replies" method="POST" class="reply-form">
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
                                    <div class="replies-container" id="replies-${data.comment_id}" style="display: none;">
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Добавляем новый комментарий в начало списка
                        commentsList.insertBefore(newComment, commentsList.firstChild);
                        
                        // Добавляем обработчики для нового комментария
                        const newLikeButton = newComment.querySelector('.like-button');
                        if (newLikeButton) {
                            newLikeButton.addEventListener('click', function() {
                                const commentId = this.dataset.commentId;
                                
                                fetch(`/comments/${commentId}/like`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const likesCount = this.querySelector('.likes-count');
                                    const img = this.querySelector('img');
                                    
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
                                });
                            });
                        }
                        
                        const newRepliesToggle = newComment.querySelector('.replies-toggle');
                        if (newRepliesToggle) {
                            newRepliesToggle.addEventListener('click', function() {
                                const commentId = this.dataset.commentId;
                                const repliesContainer = document.getElementById(`replies-${commentId}`);
                                
                                if (repliesContainer.style.display === 'none' || !repliesContainer.style.display) {
                                    repliesContainer.style.display = 'block';
                                    this.classList.add('active');
                                } else {
                                    repliesContainer.style.display = 'none';
                                    this.classList.remove('active');
                                }
                            });
                        }
                        
                        const newReplyButton = newComment.querySelector('.reply-button');
                        if (newReplyButton) {
                            newReplyButton.addEventListener('click', function() {
                                const commentId = this.dataset.commentId;
                                const replyForm = document.getElementById(`reply-form-${commentId}`);
                                
                                if (replyForm.style.display === 'none' || !replyForm.style.display) {
                                    replyForm.style.display = 'block';
                                    replyForm.querySelector('textarea').focus();
                                } else {
                                    replyForm.style.display = 'none';
                                }
                            });
                        }
                    }
                    
                    // Показываем уведомление
                    showToast('Комментарий добавлен');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Ошибка при добавлении комментария', 'error');
                });
            });
        });
        
        // Обработка отправки ответов
        document.querySelectorAll('.reply-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const commentId = this.action.split('/').pop();
                
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
                    // Очищаем поле ввода
                    this.querySelector('textarea').value = '';
                    
                    // Скрываем форму ответа
                    this.closest('.reply-form-container').style.display = 'none';
                    
                    // Обновляем счетчик ответов
                    const repliesToggle = document.querySelector(`.replies-toggle[data-comment-id="${commentId}"]`);
                    if (repliesToggle) {
                        repliesToggle.textContent = `${data.replies_count} ${data.replies_count === 1 ? 'ответ' : 'ответов'}`;
                    }
                    
                    // Показываем контейнер с ответами
                    const repliesContainer = document.getElementById(`replies-${commentId}`);
                    if (repliesContainer) {
                        repliesContainer.style.display = 'block';
                        
                        // Добавляем новый ответ в список
                        const newReply = document.createElement('div');
                        newReply.className = 'reply';
                        newReply.id = `reply-${data.reply_id}`;
                        newReply.innerHTML = `
                            <div class="d-flex">
                                <a href="/users/${data.user_id}" class="text-decoration-none me-2">
                                    <img src="${data.user_avatar}" alt="${data.user_name}" class="rounded-circle" width="24" height="24">
                                </a>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <a href="/users/${data.user_id}" class="text-decoration-none text-dark fw-bold me-2">${data.user_name}</a>
                                        <small class="text-muted">сейчас</small>
                                    </div>
                                    <div class="comment-content">${data.content}</div>
                                    
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="d-flex align-items-center me-3 like-button" data-reply-id="${data.reply_id}">
                                            <img src="/images/like.svg" alt="Лайк" width="16" height="14" class="me-1">
                                            <span class="likes-count">0</span>
                                        </div>
                                        
                                        <button class="btn btn-link text-dark p-0 ms-2 reply-to-reply-button" data-reply-id="${data.reply_id}">
                                            Ответить
                                        </button>
                                    </div>
                                    
                                    <!-- Форма ответа на ответ -->
                                    <div class="reply-to-reply-form-container" id="reply-to-reply-form-${data.reply_id}" style="display: none;">
                                        <form action="/replies/${data.reply_id}/replies" method="POST" class="reply-to-reply-form">
                                            @csrf
                                            <div class="input-group">
                                                <textarea name="content" class="form-control reply-to-reply-textarea" rows="1" placeholder="Ответить..."></textarea>
                                                <button type="submit" class="btn btn-primary reply-to-reply-submit-btn">
                                                    Отправить
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Добавляем новый ответ в начало списка
                        repliesContainer.insertBefore(newReply, repliesContainer.firstChild);
                        
                        // Добавляем обработчик для нового ответа
                        const newLikeButton = newReply.querySelector('.like-button');
                        if (newLikeButton) {
                            newLikeButton.addEventListener('click', function() {
                                const replyId = this.dataset.replyId;
                                
                                fetch(`/replies/${replyId}/like`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const likesCount = this.querySelector('.likes-count');
                                    const img = this.querySelector('img');
                                    
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
                                });
                            });
                        }
                        
                        const newReplyToReplyButton = newReply.querySelector('.reply-to-reply-button');
                        if (newReplyToReplyButton) {
                            newReplyToReplyButton.addEventListener('click', function() {
                                const replyId = this.dataset.replyId;
                                const replyToReplyForm = document.getElementById(`reply-to-reply-form-${replyId}`);
                                
                                if (replyToReplyForm.style.display === 'none' || !replyToReplyForm.style.display) {
                                    replyToReplyForm.style.display = 'block';
                                    replyToReplyForm.querySelector('textarea').focus();
                                } else {
                                    replyToReplyForm.style.display = 'none';
                                }
                            });
                        }
                    }
                    
                    // Показываем уведомление
                    showToast('Ответ добавлен');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Ошибка при добавлении ответа', 'error');
                });
            });
        });
        
        // Обработка отправки ответов на ответы
        document.querySelectorAll('.reply-to-reply-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const replyId = this.action.split('/').pop();
                
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
                    // Очищаем поле ввода
                    this.querySelector('textarea').value = '';
                    
                    // Скрываем форму ответа
                    this.closest('.reply-to-reply-form-container').style.display = 'none';
                    
                    // Показываем уведомление
                    showToast('Ответ добавлен');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Ошибка при добавлении ответа', 'error');
                });
            });
        });
    });
</script>
@endpush
@endsection 