@extends('layouts.app')

@section('title', $user->name)

@section('content')
<style>
    /* Стили для постов на странице профиля */
    .post-card {
        margin-bottom: 20px;
    }
    .hover-card {
        background-color: transparent;
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
        background-color: rgba(22, 130, 253, 0.1);
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
<div class="container">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7" style="margin-top: 20px;">
            <!-- Профиль -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <x-user-avatar :user="$user" :size="112" class="rounded-circle border border-3 border-primary" />
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
                                <a class="nav-link" data-bs-toggle="tab" href="#comments">
                                    <i class="fas fa-comments me-2"></i>Комментарии
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#bookmarks">
                                    <i class="fas fa-bookmark me-2"></i>Закладки
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Контент -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="posts">
                            @if($posts->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">У пользователя пока нет записей</p>
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
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                                {{ $posts->links() }}
                            @endif
                        </div>

                        <div class="tab-pane fade" id="comments">
                            @if($comments->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">У пользователя пока нет комментариев</p>
                                </div>
                            @else
                                @foreach($comments as $comment)
                                    <div class="comment">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-2">
                                                <x-user-avatar :user="$comment->user" :size="32" />
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <p class="comment-content">{{ $comment->content }}</p>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('posts.show', $comment->post) }}" class="text-decoration-none">
                                                <small class="text-muted">
                                                    К посту: {{ $comment->post->title }}
                                                </small>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                {{ $comments->links() }}
                            @endif
                        </div>

                        <div class="tab-pane fade" id="bookmarks">
                            @if($bookmarks->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-bookmark fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">У пользователя пока нет закладок</p>
                                </div>
                            @else
                                @foreach($bookmarks as $bookmark)
                                    <div class="post-card">
                                        <div class="card border-0 hover-card">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="me-2">
                                                        <a href="{{ route('users.show', $bookmark->post->user) }}" class="text-decoration-none">
                                                            <x-user-avatar :user="$bookmark->post->user" :size="40" />
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('users.show', $bookmark->post->user) }}" class="text-decoration-none text-dark">
                                                            <h6 class="mb-0">{{ $bookmark->post->user->name }}</h6>
                                                        </a>
                                                        <small class="text-muted">{{ $bookmark->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>

                                                <div class="post-content">
                                                    <a href="{{ route('posts.show', $bookmark->post) }}" class="text-decoration-none">
                                                        <h5 class="card-title mb-3 text-dark">
                                                            {{ $bookmark->post->title }}
                                                        </h5>
                                                        <p class="card-text text-muted mb-3">{{ Str::limit($bookmark->post->content, 200) }}</p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{ $bookmarks->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Правая колонка -->
        <div class="col-md-3" style="margin-top: 20px;">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="card-title">Статистика</h6>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                <span>Постов</span>
                            </div>
                            <span class="text-dark fw-bold">{{ $stats['posts_count'] }}</span>
                        </div>
                    </div>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-comments text-primary me-2"></i>
                                <span>Комментариев</span>
                            </div>
                            <span class="text-dark fw-bold">{{ $stats['comments_count'] }}</span>
                        </div>
                    </div>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-heart text-danger me-2"></i>
                                <span>Лайков</span>
                            </div>
                            <span class="text-dark fw-bold">{{ $stats['likes_received'] }}</span>
                        </div>
                    </div>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-bookmark text-primary me-2"></i>
                                <span>Закладок</span>
                            </div>
                            <span class="text-dark fw-bold">{{ $stats['bookmarks_count'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</div>
@endsection 