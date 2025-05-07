@extends('layouts.app')

@section('title', 'Мои черновики')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            @if($drafts->isEmpty())
                <div class="card border-0">
                <div class="card-body">
                        <div class="text-center py-5">
                            <img src="{{ asset('images/pen.svg') }}" class="mb-3" width="48" height="48" alt="Черновики">
                            <h5>У вас пока нет черновиков</h5>
                            <p class="text-muted">Создайте новый пост, чтобы начать писать</p>
                        </div>
                    </div>
                        </div>
                    @else
                <h4 class="mb-4">Мои черновики</h4>
                <div class="posts-container">
                            @foreach($drafts as $draft)
                        <div class="card border-0  mb-3">
                            <div class="card-body p-4">
                                <!-- Информация о пользователе -->
                                <div class="d-flex align-items-center mb-4">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="position-relative" style="margin-right: 12px !important;">
                                            <a href="{{ route('users.show', $draft->user) }}" class="text-decoration-none">
                                                <x-user-avatar :user="$draft->user" :size="40" class="me-2" />
                                            </a>
                                            <x-rank-icon :user="$draft->user" />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('users.show', $draft->user) }}" class="text-decoration-none text-dark fw-bold me-2">{{ $draft->user->name }}</a>
                                            <small class="text-muted">Последнее обновление: {{ $draft->updated_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <small class="text-muted me-2">{{ $draft->user->rank_name }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary rounded-pill px-3 py-1 me-2">
                                            Черновик
                                        </span>
                                        <a href="{{ route('posts.edit', $draft) }}" class="btn btn-outline-primary btn-sm">
                                                <img src="{{ asset('images/pen.svg') }}" class="me-1" width="16" height="16" alt="Редактировать">
                                                Редактировать
                                            </a>
                                        </div>
                                    </div>

                                <!-- Заголовок и контент -->
                                <h2 class="h5 mb-3">
                                    {{ $draft->title }}
                                </h2>
                                <div class="post-content mb-3">
                                    {!! Str::limit($draft->content, 200) !!}
                                </div>

                                <!-- Изображение -->
                                @if($draft->image)
                                    <div class="post-image mb-3">
                                        <img src="{{ asset('storage/' . $draft->image) }}" 
                                             class="img-fluid rounded" 
                                             alt="{{ $draft->title }}">
                                    </div>
                                @endif

                                <!-- Теги -->
                                @if($draft->tags->isNotEmpty())
                                    <div class="tags mb-3">
                                        @foreach($draft->tags as $tag)
                                            <a href="{{ route('tags.show', $tag) }}" 
                                               class="badge bg-light text-dark text-decoration-none me-2">
                                                #{{ $tag->name }}
                                            </a>
                            @endforeach
                        </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .posts-container {
        margin-top: 1rem;
    }
    .post-content {
        color: #666;
    }
    .post-image img {
        max-height: 300px;
        object-fit: cover;
    }
    .tags .badge {
        font-size: 0.875rem;
        padding: 0.5em 0.75em;
    }
</style>
@endpush
@endsection 