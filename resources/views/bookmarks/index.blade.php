@extends('layouts.app')

@section('title', 'Закладки')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Закладки</h5>
                </div>
                <div class="card-body">
                    @if($bookmarks->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/bookmark.svg') }}" class="mb-3" width="48" height="48" alt="Закладки" style="filter: brightness(0);">
                            <h5>У вас пока нет закладок</h5>
                            <p class="text-muted">Добавьте интересные посты в закладки, чтобы вернуться к ним позже</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($bookmarks as $bookmark)
                                <div class="list-group-item">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <x-user-avatar :user="$bookmark->post->user" :size="40" />
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <a href="{{ route('users.show', $bookmark->post->user) }}" class="text-decoration-none text-dark fw-bold">{{ $bookmark->post->user->name }}</a>
                                                    <small class="text-muted ms-2">{{ $bookmark->post->created_at->diffForHumans() }}</small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-{{ $bookmark->post->type === 'post' ? 'primary' : 'success' }} me-2">
                                                        {{ $bookmark->post->type === 'post' ? 'Запись' : 'Вопрос' }}
                                                    </span>
                                                    <span class="text-muted">{{ $bookmark->post->comments_count }} {{ __('comments.comments.' . min($bookmark->post->comments_count, 20)) }}</span>
                                                </div>
                                            </div>
                                            <h5 class="mb-2">
                                                <a href="{{ route('posts.show', $bookmark->post) }}" class="text-decoration-none">
                                                    {{ $bookmark->post->title }}
                                                </a>
                                            </h5>
                                            <div class="mb-2">{{ Str::limit(strip_tags($bookmark->post->content), 200) }}</div>
                                            <div class="d-flex align-items-center">
                                                @foreach($bookmark->post->tags as $tag)
                                                    <a href="{{ route('tags.show', $tag) }}" class="badge bg-secondary text-decoration-none me-1">
                                                        #{{ $tag->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $bookmarks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 