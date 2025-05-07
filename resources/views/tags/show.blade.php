@extends('layouts.app')

@section('title', '#' . $tag->name)

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <div class="card border-0 bg-transparent">
                <div class="card-header bg-transparent border-0 py-4">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/tag.svg') }}" class="me-2" width="24" height="24" alt="Теги" style="filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);">
                        <h5 class="mb-0">#{{ $tag->name }}</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($posts->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/tag.svg') }}" class="mb-3" width="48" height="48" alt="Теги" style="filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);">
                            <h5>Постов с тегом #{{ $tag->name }} пока нет</h5>
                            <p class="text-muted mb-4">Будьте первым, кто создаст пост с этим тегом</p>
                            <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                                <i class="fas fa-home me-2"></i>Вернуться на главную
                            </a>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($posts as $post)
                                <div class="col-12">
                                    <div class="card border-0">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0">
                                                    <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                                                        {{ $post->title }}
                                                    </a>
                                                </h5>
                                                <div>
                                                    <span class="badge bg-{{ $post->type === 'post' ? 'primary' : 'success' }} rounded-pill px-3 py-1 me-2">
                                                        {{ $post->type === 'post' ? 'Запись' : 'Вопрос' }}
                                                    </span>
                                                    <span class="badge bg-light text-dark rounded-pill px-3 py-1">
                                                        {{ $post->answers_count }}
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted mb-3">{{ Str::limit($post->content, 200) }}</p>
                                            <div class="d-flex align-items-center text-muted small">
                                                <span class="me-3">Автор: <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none">{{ $post->user->name }}</a></span>
                                                <span class="me-3">Создан {{ $post->created_at->diffForHumans() }}</span>
                                                <span>Просмотров: {{ $post->views_count }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Правая колонка -->
        <x-right-sidebar :popularTags="$popularTags" :isTagsPage="true" />
    </div>
</div>

@push('styles')
<style>
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
</style>
@endpush
@endsection 