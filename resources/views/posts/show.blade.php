@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-9">
            <div class="card border-0">
                <div class="card-body p-4">
                    <!-- Информация о пользователе -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center flex-grow-1">
                            <div class="position-relative" style="margin-right: 12px !important;">
                                <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none">
                                    <x-user-avatar :user="$post->user" :size="48" class="me-2" />
                                </a>
                                <x-rank-icon :user="$post->user" />
                            </div>
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none text-dark fw-bold me-2">{{ $post->user->name }}</a>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-2">{{ $post->user->rank_name }}</small>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @can('update', $post)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                            Редактировать
                                        </a>
                                    </li>
                                @endcan
                                @can('delete', $post)
                                    <li>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Вы уверены?')">
                                                Удалить
                                            </button>
                                        </form>
                                    </li>
                                @endcan
                                @cannot('update', $post)
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#reportModal">
                                            Пожаловаться
                                        </a>
                                    </li>
                                @endcannot
                            </ul>
                        </div>
                    </div>

                    <!-- Заголовок и контент -->
                    <h1 class="h3 mb-4">
                        {{ $post->title }}
                    </h1>
                    <div class="post-content mb-4">
                        @if($post->image)
                            <div class="mb-4">
                                <img src="{{ url('storage/' . $post->image) }}" alt="Изображение поста" class="img-fluid rounded">
                            </div>
                        @endif
                        {!! $post->content !!}
                    </div>

                    <!-- Теги -->
                    @if($post->tags->isNotEmpty())
                        <div class="tags mb-4">
                            @foreach($post->tags as $tag)
                                <a href="{{ route('tags.show', $tag) }}" 
                                   class="badge bg-light text-dark text-decoration-none me-2">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <!-- Действия с постом -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            @auth
                            <button class="like-button {{ $post->likedBy(auth()->user()) ? 'active' : '' }} me-4" data-post-id="{{ $post->id }}">
                                <span class="like-icon-wrapper">
                                    <svg class="like-icon" width="20" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="likes-count">{{ $post->likes_count }}</span>
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="like-button me-4">
                                <span class="like-icon-wrapper">
                                    <svg class="like-icon" width="20" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span class="likes-count">{{ $post->likes_count }}</span>
                            </a>
                            @endauth

                            <button class="btn btn-link text-dark p-0 me-4 comment-toggle" data-post-id="{{ $post->id }}">
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

                    <!-- Комментарии -->
                    <div class="comments-section mt-3" id="comments-section-{{ $post->id }}" style="display: none;">
                        <h6 class="mb-3">{{ $post->comments_count }} комментариев</h6>

                        @auth
                        <div class="comment-form-container mb-4">
                            <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="comment-form">
                                @csrf
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-2">
                                        <x-user-avatar :user="auth()->user()" :size="40" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="position-relative">
                                            <textarea name="content" class="form-control" rows="1" placeholder="Комментарий..." style="padding-right: 100px;"></textarea>
                                            <button type="submit" class="btn btn-primary btn-sm">Отправить</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @else
                        <div class="text-center py-3 mb-4">
                            <p class="mb-0">Чтобы оставить комментарий, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a></p>
                        </div>
                        @endauth

                        <div class="comments-list">
                            @foreach($post->comments as $comment)
                            <div class="comment mb-4">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-2">
                                        <a href="{{ route('users.show', $comment->user) }}">
                                            <x-user-avatar :user="$comment->user" :size="40" />
                                        </a>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                                                <span class="text-muted ms-2 small">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            @auth
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @can('update', $comment)
                                                    <li><a class="dropdown-item" href="#" onclick="editComment({{ $comment->id }})">Редактировать</a></li>
                                                    @endcan
                                                    @can('delete', $comment)
                                                    <li>
                                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Вы уверены?')">
                                                                Удалить
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endcan
                                                </ul>
                                            </div>
                                            @endauth
                                        </div>
                                        <div class="comment-content mb-2">
                                            {{ $comment->content }}
                                        </div>
                                        <div class="comment-actions">
                                            <div class="d-flex align-items-center">
                                                @auth
                                                <button class="btn btn-link text-muted p-0 me-3 comment-like-button" data-comment-id="{{ $comment->id }}">
                                                    <i class="bi bi-heart{{ $comment->likedBy(auth()->user()) ? '-fill text-danger' : '' }} like-icon"></i>
                                                    <span class="like-count ms-1">{{ $comment->likes_count }}</span>
                                                </button>
                                                <button class="btn btn-link text-muted p-0 reply-button" data-comment-id="{{ $comment->id }}">
                                                    Ответить
                                                </button>
                                                @if($comment->replies->count() > 0)
                                                <span class="replies-count" data-comment-id="{{ $comment->id }}">
                                                    {{ $comment->replies->count() }} ответов
                                                </span>
                                                @endif
                                                @else
                                                <a href="{{ route('login') }}" class="btn btn-link text-muted p-0 me-3">
                                                    <i class="bi bi-heart"></i>
                                                    <span class="ms-1">{{ $comment->likes_count }}</span>
                                                </a>
                                                <a href="{{ route('login') }}" class="btn btn-link text-muted p-0">
                                                    Ответить
                                                </a>
                                                @endauth
                                            </div>
                                        </div>

                                        <!-- Форма для ответа -->
                                        @auth
                                        <div class="reply-form mt-3" style="display: none;" id="reply-form-{{ $comment->id }}">
                                            <form action="{{ route('comments.replies.store', $comment) }}" method="POST">
                                                @csrf
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-2">
                                                        <x-user-avatar :user="auth()->user()" :size="32" />
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="position-relative">
                                                            <textarea name="content" class="form-control form-control-sm" rows="1" placeholder="Написать ответ..."></textarea>
                                                            <div class="mt-2 text-end">
                                                                <button type="button" class="btn btn-link btn-sm text-muted cancel-reply" data-comment-id="{{ $comment->id }}">Отмена</button>
                                                                <button type="submit" class="btn btn-primary btn-sm ms-2">Ответить</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        @endauth

                                        <!-- Ответы на комментарий -->
                                        @if($comment->replies->count() > 0)
                                        <div class="comment-replies">
                                            <div class="replies-list" id="replies-{{ $comment->id }}" style="display: none;">
                                                @foreach($comment->replies as $reply)
                                                <div class="reply mb-3">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-2">
                                                            <a href="{{ route('users.show', $reply->user) }}">
                                                                <x-user-avatar :user="$reply->user" :size="32" />
                                                            </a>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                                <div>
                                                                    <a href="{{ route('users.show', $reply->user) }}" class="text-decoration-none text-dark fw-bold">{{ $reply->user->name }}</a>
                                                                    <span class="text-muted ms-2 small">{{ $reply->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <div class="d-flex align-items-center">
                                                                    @auth
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                                            <i class="bi bi-three-dots"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            @can('update', $reply)
                                                                            <li><a class="dropdown-item" href="#" onclick="editComment({{ $reply->id }})">Редактировать</a></li>
                                                                            @endcan
                                                                            @can('delete', $reply)
                                                                            <li>
                                                                                <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="d-inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Вы уверены?')">
                                                                                        Удалить
                                                                                    </button>
                                                                                </form>
                                                                            </li>
                                                                            @endcan
                                                                        </ul>
                                                                    </div>
                                                                    @endauth
                                                                </div>
                                                            </div>
                                                            <div class="reply-content">
                                                                {{ $reply->content }}
                                                            </div>
                                                            <div class="comment-actions">
                                                                <div class="d-flex align-items-center">
                                                                    @auth
                                                                    <button class="btn btn-link text-muted p-0 me-3 comment-like-button" data-comment-id="{{ $reply->id }}">
                                                                        <i class="bi bi-heart{{ $reply->likedBy(auth()->user()) ? '-fill text-danger' : '' }} like-icon"></i>
                                                                        <span class="like-count ms-1">{{ $reply->likes_count }}</span>
                                                                    </button>
                                                                    <button class="btn btn-link text-muted p-0 reply-button" data-comment-id="{{ $reply->id }}">
                                                                        Ответить
                                                                    </button>
                                                                    @else
                                                                    <a href="{{ route('login') }}" class="btn btn-link text-muted p-0 me-3">
                                                                        <i class="bi bi-heart"></i>
                                                                        <span class="ms-1">{{ $reply->likes_count }}</span>
                                                                    </a>
                                                                    <a href="{{ route('login') }}" class="btn btn-link text-muted p-0">
                                                                        Ответить
                                                                    </a>
                                                                    @endauth
                                                                </div>
                                                            </div>

                                                            <!-- Форма для ответа на ответ -->
                                                            @auth
                                                            <div class="reply-form mt-3" style="display: none;" id="reply-form-{{ $reply->id }}">
                                                                <form action="{{ route('comments.replies.store', $comment) }}" method="POST">
                                                                    @csrf
                                                                    <div class="d-flex">
                                                                        <div class="flex-shrink-0 me-2">
                                                                            <x-user-avatar :user="auth()->user()" :size="32" />
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <div class="position-relative">
                                                                                <textarea name="content" class="form-control form-control-sm" rows="1" placeholder="Написать ответ..."></textarea>
                                                                                <div class="mt-2 text-end">
                                                                                    <button type="button" class="btn btn-link btn-sm text-muted cancel-reply" data-comment-id="{{ $reply->id }}">Отмена</button>
                                                                                    <button type="submit" class="btn btn-primary btn-sm ms-2">Ответить</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            @endauth
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для жалобы на пост -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Пожаловаться на пост</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            @auth
            <form action="{{ route('posts.report', $post) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Причина жалобы</label>
                        <select class="form-select" id="reason" name="reason" required>
                            <option value="">Выберите причину</option>
                            <option value="spam">Спам</option>
                            <option value="violence">Насилие</option>
                            <option value="hate">Разжигание ненависти</option>
                            <option value="harassment">Травля</option>
                            <option value="pornography">Порнография</option>
                            <option value="other">Другое</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отправить жалобу</button>
                </div>
            </form>
            @endauth
        </div>
    </div>
</div>

@push('styles')
<link href="{{ asset('css/comments.css') }}" rel="stylesheet">
<style>
    .like-button {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        background: none;
        border: none;
        color: inherit;
    }

    .like-button:hover {
        background-color: rgba(255, 59, 48, 0.1);
    }

    .like-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 19px;
        position: relative;
    }

    .like-icon {
        width: 20px;
        height: 19px;
        transition: all 0.2s ease;
    }

    .like-button.active .like-icon path {
        stroke: #ff3b30;
        fill: #ff3b30;
    }

    .likes-count {
        font-size: 0.9rem;
        color: #1a1a1a;
        min-width: 20px;
        text-align: left;
        line-height: 1;
        transition: color 0.2s ease;
    }

    .like-button.active .likes-count {
        color: #ff3b30;
        font-weight: 500;
    }

    .like-button.animate .like-icon {
        animation: likeAnimation 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes likeAnimation {
        0% { transform: scale(1); }
        50% { transform: scale(1.4); }
        100% { transform: scale(1); }
    }

    /* Стили для неактивной кнопки */
    .like-button:not(.active) .like-icon path {
        stroke: #595959;
        fill: transparent;
    }

    /* Эффект при наведении на неактивную кнопку */
    .like-button:not(.active):hover .like-icon path {
        stroke: #ff3b30;
    }

    /* Эффект при нажатии */
    .like-button:active .like-icon {
        transform: scale(0.95);
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/comments.js') }}"></script>
<script src="{{ asset('js/post-show.js') }}"></script>
@endpush
@endsection