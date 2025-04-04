@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="container" style="margin-top: 60px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <div class="card border-0 bg-transparent">
                <div class="card-header bg-transparent border-0 ">
                    <ul class="nav nav-tabs card-header-tabs border-0"  style="margin-top: 15px;">
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
                            <img src="{{ asset('images/no-posts.svg') }}" alt="Постов пока нет" width="48" height="48" class="mb-3">
                            <h5 class="fw-light mb-3">Постов пока нет</h5>
                            <p class="text-muted mb-4">Создайте свой первый пост, чтобы начать</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
                                Создать пост
                            </a>
                        </div>
                    @else
                        <div class="posts-container">
                            @foreach($posts as $post)
                                <div class="col-12 mb-4">
                                    <div class="card post-card hover-card border-0" data-post-id="{{ $post->id }}">
                                        <div class="card-body p-4">
                                            <!-- Информация о пользователе -->
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="me-2">
                                                    <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none">
                                                        <x-user-avatar :user="$post->user" :size="52" />
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

                                            <!-- Раздел комментариев (скрыт по умолчанию) -->
                                            <div id="comments-container-{{ $post->id }}" class="comments-container mt-3" style="display: none;">
                                                <!-- Количество комментариев и сортировка -->
                                                <div class="d-flex justify-content-between align-items-center mb-4">
                                                    <h6 class="mb-0">{{ $post->comments_count }} {{ trans_choice('комментариев|комментария|комментариев', $post->comments_count) }}</h6>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm text-muted bg-transparent border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            <small>По популярности</small>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item small" href="#">По популярности</a></li>
                                                            <li><a class="dropdown-item small" href="#">По дате</a></li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <!-- Форма комментария -->
                                                @auth
                                                <div class="comment-form-container mb-4">
                                                    <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="comment-form">
                                                        @csrf
                                                        <div class="d-flex align-items-start gap-2">
                                                            <div class="flex-shrink-0">
                                                                <x-user-avatar :user="Auth::user()" :size="40" />
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="position-relative">
                                                                    <textarea name="content" class="form-control" rows="2" placeholder="Комментарий..."></textarea>
                                                                    <button type="submit" class="btn btn-primary position-absolute" style="bottom: 8px; right: 8px; padding: 5px 15px; font-size: 13px;">Отправить</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                @else
                                                <div class="text-center py-3 mb-4">
                                                    <p class="mb-0">Чтобы оставить комментарий, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a></p>
                                                </div>
                                                @endauth
                                                
                                                <!-- Список комментариев -->
                                                <div class="comments-list">
                                                    @if($post->comments->isNotEmpty())
                                                        @foreach($post->comments->take(3) as $comment)
                                                            <div class="comment mb-3">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <a href="{{ route('users.show', $comment->user) }}">
                                                                            <x-user-avatar :user="$comment->user" :size="40" />
                                                                        </a>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex flex-column">
                                                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                                                <div>
                                                                                    <a href="{{ route('profile.show', $comment->user) }}" class="user-name text-decoration-none">{{ $comment->user->name }}</a>
                                                                                    <span class="comment-time ms-2">{{ $comment->created_at->diffForHumans() }}</span>
                                                                                </div>
                                                                                @auth
                                                                                <div class="dropdown">
                                                                                    <button class="btn btn-sm text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                                                        <i class="bi bi-three-dots-vertical"></i>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                                        @if(auth()->id() != $comment->user_id)
                                                                                        <li>
                                                                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#reportCommentModal{{ $comment->id }}">
                                                                                                Пожаловаться
                                                                                            </a>
                                                                                        </li>
                                                                                        @else
                                                                                        <li>
                                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">
                                                                                                Редактировать
                                                                                            </a>
                                                                                        </li>
                                                                                        <li>
                                                                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                                                                                @csrf
                                                                                                @method('DELETE')
                                                                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Вы уверены?')">
                                                                                                    Удалить
                                                                                                </button>
                                                                                            </form>
                                                                                        </li>
                                                                                        @endif
                                                                                    </ul>
                                                                                </div>
                                                                                @endauth
                                                                            </div>
                                                                            <div class="comment-content mb-2">
                                                                                {{ $comment->content }}
                                                                            </div>
                                                                            <div class="d-flex align-items-center">
                                                                                @auth
                                                                                <div class="d-flex align-items-center me-3 like-button" data-comment-id="{{ $comment->id }}">
                                                                                    <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1 {{ $comment->likedBy(auth()->user()) ? 'liked' : '' }}">
                                                                                    <span class="likes-count {{ $comment->likedBy(auth()->user()) ? 'active' : '' }}">{{ $comment->likes_count }}</span>
                                                                                </div>
                                                                                <div class="d-flex align-items-center me-3">
                                                                                    <a href="#" class="text-decoration-none text-muted small reply-button" data-comment-id="{{ $comment->id }}">Ответить</a>
                                                                                </div>
                                                                                @if($comment->replies_count > 0)
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="replies-count" data-comment-id="{{ $comment->id }}">{{ $comment->replies_count }} {{ $comment->replies_count == 1 ? 'ответ' : ($comment->replies_count > 1 && $comment->replies_count < 5 ? 'ответа' : 'ответов') }}</span>
                                                                                </div>
                                                                                @endif
                                                                                @else
                                                                                <div class="d-flex align-items-center me-3">
                                                                                    <a href="{{ route('login') }}" class="text-decoration-none text-muted small d-flex align-items-center">
                                                                                        <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1">
                                                                                        <span>{{ $comment->likes_count }}</span>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="d-flex align-items-center me-3">
                                                                                    <a href="{{ route('login') }}" class="text-decoration-none text-muted small">Ответить</a>
                                                                                </div>
                                                                                @if($comment->replies_count > 0)
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="text-muted small">{{ $comment->replies_count }} {{ $comment->replies_count == 1 ? 'ответ' : ($comment->replies_count > 1 && $comment->replies_count < 5 ? 'ответа' : 'ответов') }}</span>
                                                                                </div>
                                                                                @endif
                                                                                @endauth
                                                                            </div>
                                                                            
                                                                            <!-- Блок ответов -->
                                                                            @if($comment->replies_count > 0)
                                                                            <div id="replies-{{ $comment->id }}" class="replies-container mt-3" style="display: none;">
                                                                                @foreach($comment->replies as $reply)
                                                                                <div class="reply mb-3">
                                                                                    <div class="d-flex">
                                                                                        <div class="flex-shrink-0 me-3">
                                                                                            <a href="{{ route('profile.show', $reply->user) }}">
                                                                                                <img src="{{ $reply->user->avatar }}" alt="{{ $reply->user->name }}" class="rounded-circle" width="30" height="30">
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class="flex-grow-1">
                                                                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                                                                <div>
                                                                                                    <a href="{{ route('profile.show', $reply->user) }}" class="user-name text-decoration-none">{{ $reply->user->name }}</a>
                                                                                                    <span class="comment-time ms-2">{{ $reply->created_at->diffForHumans() }}</span>
                                                                                                </div>
                                                                                                @auth
                                                                                                <div class="dropdown">
                                                                                                    <button class="btn btn-sm text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                                                                        <i class="bi bi-three-dots-vertical"></i>
                                                                                                    </button>
                                                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                                                        @if(auth()->id() != $reply->user_id)
                                                                                                        <li>
                                                                                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#reportReplyModal{{ $reply->id }}">
                                                                                                                Пожаловаться
                                                                                                            </a>
                                                                                                        </li>
                                                                                                        @else
                                                                                                        <li>
                                                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editReplyModal{{ $reply->id }}">
                                                                                                                Редактировать
                                                                                                            </a>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <form action="{{ route('replies.destroy', $reply) }}" method="POST">
                                                                                                                @csrf
                                                                                                                @method('DELETE')
                                                                                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Вы уверены?')">
                                                                                                                    Удалить
                                                                                                                </button>
                                                                                                            </form>
                                                                                                        </li>
                                                                                                        @endif
                                                                                                    </ul>
                                                                                                </div>
                                                                                                @endauth
                                                                                            </div>
                                                                                            <div class="reply-content mb-2">
                                                                                                {{ $reply->content }}
                                                </div>
                                                <div class="d-flex align-items-center">
                                                                                                @auth
                                                                                                <div class="d-flex align-items-center me-3 like-button" data-reply-id="{{ $reply->id }}">
                                                                                                    <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1 {{ $reply->likedBy(auth()->user()) ? 'liked' : '' }}">
                                                                                                    <span class="likes-count {{ $reply->likedBy(auth()->user()) ? 'active' : '' }}">{{ $reply->likes_count }}</span>
                                                                                                </div>
                                                                                                @else
                                                                                                <div class="d-flex align-items-center me-3">
                                                                                                    <a href="{{ route('login') }}" class="text-decoration-none text-muted small d-flex align-items-center">
                                                                                                        <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1">
                                                                                                        <span>{{ $reply->likes_count }}</span>
                                                                                                    </a>
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
                                                        @endforeach
                                                        
                                                        @if($post->comments_count > 3)
                                                            <a href="{{ route('posts.show', $post) }}" class="comments-view-all">Показать все комментарии ({{ $post->comments_count }})</a>
                                                        @endif
                                                    @else
                                                        <div class="text-center py-3">
                                                            <p class="text-muted mb-0">Будьте первым, кто оставит комментарий!</p>
                                                        </div>
                                                    @endif
                                                </div>
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

        <!-- Правая колонка -->
        <x-right-sidebar :popularTags="$popularTags" :topUsers="$topUsers" :recentAnswers="$recentAnswers" :isHomePage="true" />
    </div>
</div>

@push('styles')
<!-- Стили для главной страницы перенесены в общий файл CSS app.css -->
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка клика по счетчику ответов
    document.querySelectorAll('.replies-count').forEach(counter => {
        counter.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const commentId = this.dataset.commentId;
            const repliesContainer = document.querySelector(`#replies-${commentId}`);
            
            if (repliesContainer) {
                // Переключаем отображение блока ответов
                if (repliesContainer.style.display === 'none') {
                    repliesContainer.style.display = 'block';
                } else {
                    repliesContainer.style.display = 'none';
                }
            }
        });
    });

    // Функция для обработки клика на кнопку лайка
    function handleLikeButtonClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const commentId = this.dataset.commentId;
        const postId = this.dataset.postId;
        const url = commentId ? `/comments/${commentId}/like` : `/posts/${postId}/like`;
        const likesCount = this.querySelector('.likes-count');
        const likeImg = this.querySelector('img');
        
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
                likesCount.textContent = data.likes_count;
                
                // Добавляем/удаляем класс active для счетчика
                if (data.liked) {
                    likesCount.classList.add('active');
                } else {
                    likesCount.classList.remove('active');
                }
            }
            
            // Обновляем стили иконок
            if (likeImg) {
                if (data.liked) {
                    likeImg.classList.add('liked');
                } else {
                    likeImg.classList.remove('liked');
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

    // Обработка кнопок "Ответить" на комментариях
    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const commentId = this.dataset.commentId;
            const postId = this.closest('.post-card').dataset.postId;
            
            // Перенаправляем на страницу поста с якорем на комментарий
            window.location.href = `/posts/${postId}#comment-${commentId}`;
        });
    });

    // Лайки
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', handleLikeButtonClick);
    });
    
    // Комментарии - показать/скрыть комментарии
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
                                const commentsCountElements = document.querySelectorAll(`.comment-button[data-post-id="${postId}"] .comments-count`);
                                commentsCountElements.forEach(element => {
                                    element.textContent = parseInt(element.textContent) + 1;
                                });
                                
                                // Обновляем заголовок с количеством комментариев
                                const commentsHeader = commentsContainer.querySelector('h6:first-child');
                                if (commentsHeader) {
                                    const newCount = parseInt(commentsHeader.textContent.split(' ')[0]) + 1;
                                    commentsHeader.textContent = `${newCount} ${getCommentCountText(newCount)}`;
                                }
                                
                                // Очищаем форму
                                this.querySelector('textarea').value = '';
                                
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
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <a href="${data.user_url}" class="text-decoration-none user-name me-2">${data.user_name}</a>
                                                        <span class="comment-time">только что</span>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                                <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#">Ответить</a></li>
                                                            <li><a class="dropdown-item" href="#">Редактировать</a></li>
                                                            <li><a class="dropdown-item text-danger" href="#">Удалить</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="comment-content">
                                                    ${data.content}
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex align-items-center me-3 like-button" data-comment-id="${data.comment_id}">
                                                        <img src="/images/like.svg" alt="Лайк" width="18" height="16" class="me-1">
                                                        <span class="likes-count">0</span>
                                                    </div>
                                                    <div class="d-flex align-items-center me-3">
                                                        <a href="#" class="text-decoration-none text-muted small">Ответить</a>
                                                    </div>
                                                    <span class="text-muted small">0 ответов</span>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    
                                    // Если это первый комментарий, заменяем сообщение "Будьте первым"
                                    const noCommentsMessage = commentsList.querySelector('.text-center.py-3');
                                    if (noCommentsMessage) {
                                        commentsList.innerHTML = '';
                                    }
                                    
                                    commentsList.appendChild(newComment);
                                    
                                    // Добавляем обработчик клика для нового комментария
                                    const newLikeButton = newComment.querySelector('.like-button');
                                    if (newLikeButton) {
                                        newLikeButton.addEventListener('click', handleLikeButtonClick);
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

// Функция для правильного склонения слова "комментарий"
function getCommentCountText(count) {
    const lastDigit = count % 10;
    const lastTwoDigits = count % 100;
    
    if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
        return 'комментариев';
    }
    
    if (lastDigit === 1) {
        return 'комментарий';
    }
    
    if (lastDigit >= 2 && lastDigit <= 4) {
        return 'комментария';
    }
    
    return 'комментариев';
}
</script>
@endpush

<!-- Модальные окна для жалоб на посты -->
@foreach($posts as $post)
<div class="modal fade" id="reportPostModal{{ $post->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Пожаловаться на пост</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('posts.report', $post) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Тип жалобы</label>
                        <select name="type" class="form-select" required>
                            <option value="">Выберите тип жалобы</option>
                            <option value="спам">Спам</option>
                            <option value="оскорбление">Оскорбление</option>
                            <option value="неприемлемый контент">Неприемлемый контент</option>
                            <option value="нарушение авторских прав">Нарушение авторских прав</option>
                            <option value="другое">Другое</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Причина жалобы</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Опишите подробнее причину жалобы..." minlength="10" maxlength="1000"></textarea>
                        <div class="form-text">Минимум 10 символов</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отправить жалобу</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection 