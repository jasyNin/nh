@extends('layouts.app')

@section('title', $user->name)

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <x-user-avatar :user="$user" :size="150" class="mb-3" />
                    <h4 class="card-title">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    @if($user->bio)
                        <p class="card-text">{{ $user->bio }}</p>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">Статистика</div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Посты
                        <span class="badge bg-primary rounded-pill">{{ $stats['posts_count'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Комментарии
                        <span class="badge bg-primary rounded-pill">{{ $stats['comments_count'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Получено лайков
                        <span class="badge bg-primary rounded-pill">{{ $stats['likes_received'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        Закладки
                        <span class="badge bg-primary rounded-pill">{{ $stats['bookmarks_count'] }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#posts">Посты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#comments">Комментарии</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#bookmarks">Закладки</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="posts">
                            @if($posts->isEmpty())
                                <p class="text-center text-muted">У пользователя пока нет постов</p>
                            @else
                                @foreach($posts as $post)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0">
                                                    <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                                        {{ $post->title }}
                                                    </a>
                                                </h5>
                                                <span class="badge bg-{{ $post->type === 'post' ? 'primary' : 'success' }} rounded-pill px-3 py-1">
                                                    {{ $post->type === 'post' ? 'Запись' : 'Вопрос' }}
                                                </span>
                                            </div>
                                            <p class="card-text">{{ Str::limit($post->content, 200) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                                <div>
                                                    <span class="badge bg-primary me-2">{{ $post->likes()->count() }} лайков</span>
                                                    <span class="badge bg-secondary">{{ $post->comments()->count() }} комментариев</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{ $posts->links() }}
                            @endif
                        </div>

                        <div class="tab-pane fade" id="comments">
                            @if($comments->isEmpty())
                                <p class="text-center text-muted">У пользователя пока нет комментариев</p>
                            @else
                                @foreach($comments as $comment)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <p class="card-text">{{ $comment->content }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    К посту: <a href="{{ route('posts.show', $comment->post) }}" class="text-decoration-none">{{ $comment->post->title }}</a>
                                                    • {{ $comment->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{ $comments->links() }}
                            @endif
                        </div>

                        <div class="tab-pane fade" id="bookmarks">
                            @if($bookmarks->isEmpty())
                                <p class="text-center text-muted">У пользователя пока нет закладок</p>
                            @else
                                @foreach($bookmarks as $bookmark)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="{{ route('posts.show', $bookmark->post) }}" class="text-decoration-none">
                                                    {{ $bookmark->post->title }}
                                                </a>
                                            </h5>
                                            <p class="card-text">{{ Str::limit($bookmark->post->content, 200) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    Автор: <a href="{{ route('users.show', $bookmark->post->user) }}" class="text-decoration-none">{{ $bookmark->post->user->name }}</a>
                                                    • {{ $bookmark->created_at->diffForHumans() }}
                                                </small>
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
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">Популярные теги</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($user->posts->pluck('tags')->flatten()->unique('id')->take(10) as $tag)
                            <a href="{{ route('tags.show', $tag) }}" class="badge bg-secondary text-decoration-none">
                                #{{ $tag->name }}
                                <span class="ms-1">{{ $tag->posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Топ пользователей</div>
                <div class="list-group list-group-flush">
                    @foreach($user->posts->pluck('user')->unique('id')->take(5) as $relatedUser)
                        @if($relatedUser->id !== $user->id)
                            <a href="{{ route('users.show', $relatedUser) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <x-user-avatar :user="$relatedUser" :size="32" class="me-3" />
                                <div>
                                    <h6 class="mb-0">{{ $relatedUser->name }}</h6>
                                    <small class="text-muted">{{ $relatedUser->posts_count }} {{ __('posts.posts.' . min($relatedUser->posts_count, 20)) }}</small>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 