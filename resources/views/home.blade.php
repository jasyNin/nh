@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <div class="card border-0 bg-transparent">
                <div class="card-header bg-transparent border-0">
                    <ul class="nav nav-tabs card-header-tabs border-0">
                        <li class="nav-item">
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3" style="filter: invert(32%) sepia(98%) saturate(1234%) hue-rotate(210deg) brightness(97%) contrast(101%);">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
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
                                                <div class="ms-auto">
                                                    <span class="badge bg-{{ $post->type === 'post' ? 'primary' : 'success' }} rounded-pill px-3 py-1">
                                                        {{ $post->type === 'post' ? 'Запись' : 'Вопрос' }}
                                                    </span>
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                                    </svg>
                                                    <span class="likes-count" style="pointer-events: none;">{{ $post->likes_count }}</span>
                                                </div>
                                                <div class="d-flex align-items-center me-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                    </svg>
                                                    <span>{{ $post->comments_count }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                                        <path d="M17 1l4 4-4 4"></path>
                                                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                                        <path d="M7 23l-4-4 4-4"></path>
                                                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                                    </svg>
                                                    <span>{{ $post->reposts_count }}</span>
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
        <x-right-sidebar :popularTags="$popularTags" :topUsers="$topUsers" :isHomePage="true" />
    </div>
</div>

@push('styles')
<style>
.nav-tabs {
    justify-content: flex-start;
    margin-left: 1px;
}

.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    padding: 0.5rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    color: #1682FD;
    border: none;
}

.nav-tabs .nav-link.active {
    color: #1682FD;
    border: none;
    border-bottom: 2px solid #1682FD;
}

.posts-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.post-card {
    width: 100%;
}

.hover-card {
    transition: all 0.3s ease;
    background-color: white;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 500;
}

.card-title:hover {
    color: #1682FD !important;
}

.card-text {
    line-height: 1.6;
}

.post-image img {
    max-height: 400px;
    object-fit: cover;
    width: 100%;
}

.tags .badge {
    font-weight: 400;
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
}

.tags .badge:hover {
    background-color: #e9ecef !important;
    color: #1682FD !important;
}

.btn-primary {
    background-color: #1682FD;
    border-color: #1682FD;
}

.btn-primary:hover {
    background-color: #1470e0;
    border-color: #1470e0;
}

.post-content {
    cursor: pointer;
}

.post-content:hover .card-title {
    color: #1682FD !important;
}

.post-content:hover .card-text {
    color: #495057 !important;
}

.post-content:hover .post-image img {
    opacity: 0.9;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.dataset.postId;
            const likesCount = this.querySelector('.likes-count');
            
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Обновляем счетчик лайков на всех карточках с этим постом
                document.querySelectorAll(`.like-button[data-post-id="${postId}"] .likes-count`).forEach(count => {
                    count.textContent = data.likes_count;
                });
            });
        });
    });
});
</script>
@endpush
@endsection 