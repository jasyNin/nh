@extends('layouts.app')

@section('title', $user->name)

@section('content')
<style>
    /* Стили для постов на странице профиля */
    .post-card {
        margin-bottom: 20px;
    }
    .post-card .card {
        margin-bottom: 0;
    }
    .post-card .card-body {
        padding-bottom: 16px;
        transition: padding-bottom 0.2s;
    }
    .post-card .card-body.p-4 {
        padding: 16px !important;
    }
    .post-card .comments-section {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
    /* Если секция комментариев видима (display не none), убираем отступ */
    .post-card .comments-section:not([style*="display: none"]) ~ .card-body,
    .post-card .comments-section:not([hidden]) ~ .card-body {
        padding-bottom: 0 !important;
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
                    <div class="d-flex align-items-start mb-4">
                        <div class="position-relative">
                        <x-user-avatar :user="$user" :size="112" class="rounded-circle border border-3 border-primary" />
                        </div>
                        <div class="ms-4 flex-grow-1">
                            <h3 class="mb-1" style="font-size: 36px;">{{ $user->name }}</h3>
                            <p class="text-muted mb-2" style="font-size: 22.5px;">{{ $user->email }}</p>
                            @if($user->bio)
                                <p class="mb-3" style="font-size: 22.5px;">{{ $user->bio }}</p>
                            @endif
                        </div>
                        <div class="text-center" style="min-width: 120px;">
                            <img src="{{ asset('images/' . $user->rank_icon) }}" alt="{{ $user->rank_name }}" width="58" height="58" class="mb-2">
                            <div style="font-size: 22px; color: #272727; white-space: nowrap;">{{ $user->rank_name }}</div>
                            <div style="font-size: 15px; color: #272727;">{{ $user->rating }} баллов</div>
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
                                    <div class="card border-0">
                                        <div class="card-body p-4">
                                            <x-post-card :post="$post" />
                                        </div>
                                        <x-comments-section :post="$post" />
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
                            <p class="text-muted">У пользователя пока нет комментариев</p>
                        </div>
                    @else
                        <div class="comments-list">
                            @foreach($comments as $comment)
                                <div class="comment-card mb-3">
                                    <div class="card border-0">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-start">
                                                <div class="position-relative me-3">
                                                    <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none">
                                                        <x-user-avatar :user="$comment->user" :size="48" />
                                                    </a>
                                                    <x-rank-icon :user="$comment->user" />
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none">
                                                            <span class="fw-bold text-dark">{{ $comment->user->name }}</span>
                                                        </a>
                                                        <span class="text-muted ms-2" style="font-size: 14px;">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="mb-2" style="font-size: 14px;">{{ $comment->content }}</div>
                                                    <div>
                                                        <a href="{{ route('posts.show', $comment->post) }}" class="text-decoration-none">
                                                            <span class="text-muted" style="font-size: 14px;">К посту: {{ $comment->post->title }}</span>
                                                        </a>
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

                <style>
                    .comment-card .card {
                        background: #FFFFFF;
                        border-radius: 12px;
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
                    }
                </style>

                <div class="tab-pane fade" id="bookmarks">
                    @if($bookmarks->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-bookmark fa-3x text-muted mb-3"></i>
                            <p class="text-muted">У пользователя пока нет закладок</p>
                        </div>
                    @else
                        <div class="bookmarks-list">
                            @foreach($bookmarks as $bookmark)
                                <div class="bookmark-card mb-3">
                                    <div class="card border-0">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-start">
                                                <div class="position-relative me-3">
                                                    <a href="{{ route('users.show', $bookmark->post->user) }}" class="text-decoration-none">
                                                        <x-user-avatar :user="$bookmark->post->user" :size="48" />
                                                    </a>
                                                    <x-rank-icon :user="$bookmark->post->user" />
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <a href="{{ route('users.show', $bookmark->post->user) }}" class="text-decoration-none">
                                                            <span class="fw-bold text-dark">{{ $bookmark->post->user->name }}</span>
                                                        </a>
                                                        <span class="text-muted ms-2" style="font-size: 14px;">{{ $bookmark->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="post-content">
                                                        <a href="{{ route('posts.show', $bookmark->post) }}" class="text-decoration-none">
                                                            <h5 class="card-title mb-3 text-dark">{{ $bookmark->post->title }}</h5>
                                                            <p class="text-muted mb-0" style="font-size: 14px;">{{ Str::limit($bookmark->post->content, 200) }}</p>
                                                        </a>
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
                            <span class="text-dark fw-bold">{{ $user->rating }}</span>
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