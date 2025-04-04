@extends('layouts.app')

@section('title', 'Главная')

@section('content')
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
                                                                            <x-user-avatar :user="$answer->user" :size="32" />
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
                                                            <div class="comment mb-3">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <a href="{{ route('users.show', $comment->user) }}">
                                                                            <x-user-avatar :user="$comment->user" :size="32" />
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
                                                                            <div class="d-flex align-items-center reply-button" data-comment-id="{{ $comment->id }}">
                                                                                <a href="#" class="text-decoration-none text-muted small">Ответить</a>
                                                                            </div>
                                                                        </div>
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
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                                                    </svg>
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
                                                    <img src="${data.user_avatar}" alt="${data.user_name}" class="rounded-circle" width="32" height="32">
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
        const commentElement = this.closest('.comment');
        
        // Проверяем, есть ли уже форма для ответа
        let replyForm = commentElement.querySelector('.reply-form-container');
        
        // Если форма уже есть, просто переключаем её видимость
        if (replyForm) {
            if (replyForm.style.display === 'none') {
                replyForm.style.display = 'block';
                replyForm.querySelector('textarea').focus();
            } else {
                replyForm.style.display = 'none';
            }
            return;
        }
        
        // Если формы нет, создаем её
        replyForm = document.createElement('div');
        replyForm.className = 'reply-form-container mt-3';
        replyForm.innerHTML = `
            <form class="reply-form" action="/comments/${commentId}/replies" method="POST">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                <div class="input-group">
                    <textarea name="content" class="form-control" rows="1" placeholder="Ответить..." style="border-top-right-radius: 0; border-bottom-right-radius: 0;"></textarea>
                    <button type="submit" class="btn" style="background-color: #1682FD; color: white; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                    </button>
                </div>
            </form>
        `;
        
        // Добавляем форму после содержимого комментария
        commentElement.querySelector('.flex-grow-1').appendChild(replyForm);
        
        // Фокусируемся на текстовом поле
        replyForm.querySelector('textarea').focus();
        
        // Обрабатываем отправку ответа
        replyForm.querySelector('form').addEventListener('submit', function(event) {
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
                    // Скрываем форму
                    replyForm.style.display = 'none';
                    
                    // Очищаем форму
                    replyForm.querySelector('form').reset();
                    
                    // Создаем всплывающее сообщение
                    const toast = document.createElement('div');
                    toast.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                    toast.style.zIndex = '9999';
                    toast.textContent = 'Ответ отправлен';
                    
                    document.body.appendChild(toast);
                    
                    // Удаляем сообщение через 2 секунды
                    setTimeout(() => {
                        toast.remove();
                    }, 2000);
                }
            });
        });
    }
    
    // Добавляем обработчики для всех кнопок "Ответить"
    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', handleReplyButtonClick);
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