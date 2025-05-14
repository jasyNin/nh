@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="container main-content-container">
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
            <!-- Посты -->
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
                                <div class="post-with-comments">
                                    <x-post-card :post="$post" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Правая колонка -->
        <div class="col-md-3 right-sidebar" style="margin-top: 20px;">
            @if(count($popularTags) > 0)
                <div class="card mb-4 border-0">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="card-title">Популярные теги</h6>
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
            @auth
                @if($viewedPosts->isNotEmpty())
                    <div class="card mb-4 border-0">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h6 class="card-title">История просмотров</h6>
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
            @php
                $regularUsers = $users->filter(function($user) {
                    return !in_array($user->rank, ['bot', 'moderator', 'admin']);
                })->sortBy(function($user) {
                    $rankOrder = [
                        'supermind' => 1,
                        'master' => 2,
                        'erudite' => 3,
                        'expert' => 4,
                        'student' => 5,
                        'novice' => 6
                    ];
                    return [$rankOrder[$user->rank] ?? 999, -$user->rating];
                });

                $specialUsers = $users->filter(function($user) {
                    return in_array($user->rank, ['bot', 'moderator', 'admin']);
                })->sortByDesc('rating');

                $sortedUsers = $regularUsers->concat($specialUsers);
            @endphp
            @if(count($users) > 0)
                <div class="card mb-4 border-0" style="border-radius: 8px;">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="card-title">Топ пользователей</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($sortedUsers->take(3) as $user)
                            <a href="{{ route('users.show', $user) }}" class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative">
                                        <x-user-avatar :user="$user" :size="40" class="me-3" style="margin-right: 12px !important;" />
                                        <x-rank-icon :user="$user" />
                                    </div>
                                    <div style="margin-left: 12px;">
                                        <div class="user-name fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->rank_name }}</small>
                                        <small class="text-muted d-block">{{ $user->rating }} баллов</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush

<x-modals :posts="$posts" />
@endsection 