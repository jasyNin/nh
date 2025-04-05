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
                                                    
                                                    @auth
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

                                            <!-- Статистика -->
                                            <div class="d-flex align-items-center text-muted">
                                                <div class="me-3">
                                                    <i class="fas fa-eye me-1"></i>
                                                    {{ $post->views_count }}
                                                </div>
                                                <div class="me-3">
                                                    <i class="fas fa-comment me-1"></i>
                                                    {{ $post->comments_count }}
                                                </div>
                                                <div class="me-3">
                                                    <i class="fas fa-heart me-1"></i>
                                                    {{ $post->likes_count }}
                                                </div>
                                                @if($post->type === 'question')
                                                    <div>
                                                        <i class="fas fa-reply me-1"></i>
                                                        {{ $post->answers_count }}
                                                    </div>
                                                @endif
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
        <div class="col-md-3">
            <div class="right-sidebar">
                <!-- Популярные теги -->
                @if($popularTags->isNotEmpty())
                    <div class="card border-0 mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Популярные теги</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($popularTags as $tag)
                                    <a href="{{ route('tags.show', $tag) }}" 
                                       class="badge bg-light text-dark text-decoration-none">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Топ пользователей -->
                @if($topUsers->isNotEmpty())
                    <div class="card border-0 mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Топ пользователей</h6>
                        </div>
                        <div class="card-body">
                            @foreach($topUsers as $user)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-2">
                                        <a href="{{ route('users.show', $user) }}" class="text-decoration-none">
                                            <x-user-avatar :user="$user" :size="32" />
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('users.show', $user) }}" class="text-decoration-none text-dark">
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                        </a>
                                        <small class="text-muted">{{ $user->posts_count }} постов</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Последние ответы -->
                @if($recentAnswers->isNotEmpty())
                    <div class="card border-0">
                        <div class="card-header">
                            <h6 class="mb-0">Последние ответы</h6>
                        </div>
                        <div class="card-body">
                            @foreach($recentAnswers as $answer)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2">
                                            <a href="{{ route('users.show', $answer->user) }}" class="text-decoration-none">
                                                <x-user-avatar :user="$answer->user" :size="24" />
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{ route('users.show', $answer->user) }}" class="text-decoration-none text-dark">
                                                <h6 class="mb-0">{{ $answer->user->name }}</h6>
                                            </a>
                                            <small class="text-muted">{{ $answer->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">{{ Str::limit($answer->content, 100) }}</p>
                                    <a href="{{ route('posts.show', $answer->post) }}" class="text-decoration-none">
                                        <small class="text-primary">Читать далее</small>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 