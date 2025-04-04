@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <div class="card border-0">
                <div class="card-body p-4">
                    <!-- Информация о пользователе -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center flex-grow-1">
                            <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none">
                                <x-user-avatar :user="$post->user" :size="48" class="me-3" />
                            </a>
                            <div>
                                <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
                                <div class="text-muted small">{{ $post->created_at->diffForHumans() }}</div>
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
                    <h1 class="h3 mb-4">{{ $post->title }}</h1>
                    <div class="post-content mb-4">
                        {!! $post->content !!}
                    </div>

                    <!-- Изображение -->
                    @if($post->image)
                        <div class="post-image mb-4">
                            <img src="{{ asset('storage/' . $post->image) }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $post->title }}">
                        </div>
                    @endif

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

                    <!-- Комментарии -->
                    <div class="comments-section mb-4">
                        <h6 class="mb-3">{{ $post->comments_count }} {{ __('posts.comments.' . min($post->comments_count, 20)) }}</h6>
                        
                        <div class="comments-container">
                        @auth
                                <div class="comment-form-container mb-4">
                                    <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="comment-form">
                                @csrf
                                        <div class="d-flex align-items-start gap-2">
                                            <div class="flex-shrink-0">
                                                <x-user-avatar :user="Auth::user()" :size="40" />
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="position-relative">
                                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="2" placeholder="Комментарий...">{{ old('content') }}</textarea>
                                                    <button type="submit" class="btn btn-primary position-absolute" style="bottom: 8px; right: 8px; padding: 5px 15px; font-size: 13px;">Отправить</button>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                    @enderror
                                </div>
                                            </div>
                                        </div>
                            </form>
                                </div>
                        @else
                            <div class="text-center py-4">
                                <p class="mb-2">Чтобы оставить комментарий, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a></p>
                            </div>
                        @endauth

                            <!-- Список комментариев -->
                            <div class="comments-list">
                                @if($post->comments->isNotEmpty())
                                @foreach($post->comments as $comment)
                                        <div class="comment" id="comment-{{ $comment->id }}">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <a href="{{ route('users.show', $comment->user) }}">
                                                    <x-user-avatar :user="$comment->user" :size="40" />
                                                </a>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                            <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none user-name me-2">{{ $comment->user->name }}</a>
                                                            <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    @auth
                                                    <div class="dropdown">
                                                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                                <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            @can('update', $comment)
                                                                <li>
                                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">
                                                                        Редактировать
                                                                    </a>
                                                                </li>
                                                            @endcan
                                                            @can('delete', $comment)
                                                                <li>
                                                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Вы уверены?')">
                                                                            Удалить
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endcan
                                                            @cannot('update', $comment)
                                                                <li>
                                                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#reportCommentModal{{ $comment->id }}">
                                                                        Пожаловаться
                                                                    </a>
                                                                </li>
                                                            @endcannot
                                                        </ul>
                                                    </div>
                                                    @endauth
                                                </div>
                                                <div class="comment-content mb-2">
                                                    {{ $comment->content }}
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    @auth
                                                        <div class="d-flex align-items-center me-3 like-button" data-comment-id="{{ $comment->id }}">
                                                            <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1 {{ $comment->likedBy(auth()->user()) ? 'liked' : '' }}">
                                                            <span class="likes-count {{ $comment->likedBy(auth()->user()) ? 'active' : '' }}">{{ $comment->likes_count }}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center me-3">
                                                            <a href="#" class="text-decoration-none text-muted small reply-button" data-comment-id="{{ $comment->id }}">Ответить</a>
                                                        </div>
                                                        @if($comment->replies->count() > 0)
                                                        <div class="d-flex align-items-center">
                                                            <span class="replies-count" data-comment-id="{{ $comment->id }}">{{ $comment->replies->count() }} {{ trans_choice('posts.replies', $comment->replies->count()) }}</span>
                                                        </div>
                                                        @endif
                                                    @else
                                                        <div class="d-flex align-items-center me-3">
                                                            <a href="{{ route('login') }}" class="text-decoration-none text-muted small d-flex align-items-center">
                                                                <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1">
                                                                <span>{{ $comment->likes_count }}</span>
                                                            </a>
                                                        </div>
                                                        <div class="d-flex align-items-center me-3">
                                                            <a href="{{ route('login') }}" class="text-decoration-none text-muted small">Ответить</a>
                                                        </div>
                                                        @if($comment->replies->count() > 0)
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-muted small">{{ $comment->replies->count() }} {{ trans_choice('posts.replies', $comment->replies->count()) }}</span>
                                                        </div>
                                                        @endif
                                                    @endauth
                                                </div>

                                                    <!-- Форма для ответа на комментарий -->
                                                    @auth
                                                <div class="reply-form mt-3" style="display: none;" id="reply-form-{{ $comment->id }}">
                                                        <form action="{{ route('comments.replies.store', $comment) }}" method="POST" class="reply-form-inner">
                                                        @csrf
                                                            <div class="d-flex align-items-start gap-2">
                                                                <div class="flex-shrink-0">
                                                                    <x-user-avatar :user="Auth::user()" :size="32" />
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <div class="position-relative">
                                                            <textarea name="content" class="form-control" rows="2" placeholder="Напишите ответ..."></textarea>
                                                                        <button type="submit" class="btn btn-primary position-absolute" style="bottom: 8px; right: 8px; padding: 5px 15px; font-size: 13px;">Отправить</button>
                                                                    </div>
                                                                    <div class="d-flex justify-content-end mt-2">
                                                                        <button type="button" class="btn btn-link text-muted small p-0 cancel-reply" data-comment-id="{{ $comment->id }}">Отмена</button>
                                                                    </div>
                                                        </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                    @endauth

                                                <!-- Ответы на комментарий -->
                                                    @if($comment->replies && $comment->replies->isNotEmpty())
                                                    <div class="replies mt-3" id="replies-{{ $comment->id }}" style="display: none;">
                                                        @foreach($comment->replies as $reply)
                                                                <div class="comment-reply mb-3" id="reply-{{ $reply->id }}">
                                                                    <div class="d-flex">
                                                                        <div class="flex-shrink-0 me-3">
                                                                            <a href="{{ route('users.show', $reply->user) }}">
                                                                                <x-user-avatar :user="$reply->user" :size="32" />
                                                                            </a>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                <div>
                                                                                    <a href="{{ route('users.show', $reply->user) }}" class="text-decoration-none user-name me-2">{{ $reply->user->name }}</a>
                                                                                    <span class="comment-time">{{ $reply->created_at->diffForHumans() }}</span>
                                                                                </div>
                                                                                @auth
                                                                                <div class="dropdown">
                                                                                    <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                                                            <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                            <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                            <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                                        </svg>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                                        @can('update', $reply)
                                                                                            <li>
                                                                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editReplyModal{{ $reply->id }}">
                                                                                                    Редактировать
                                                                                                </a>
                                                                                            </li>
                                                                                        @endcan
                                                                                        @can('delete', $reply)
                                                                                            <li>
                                                                                                <form action="{{ route('replies.destroy', $reply) }}" method="POST">
                                                                                                    @csrf
                                                                                                    @method('DELETE')
                                                                                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Вы уверены?')">
                                                                                                        Удалить
                                                                                                    </button>
                                                                                                </form>
                                                                                            </li>
                                                                                        @endcan
                                                                                        @cannot('update', $reply)
                                                                                            <li>
                                                                                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#reportReplyModal{{ $reply->id }}">
                                                                                                    Пожаловаться
                                                                                                </a>
                                                                                            </li>
                                                                                        @endcannot
                                                                                    </ul>
                                                                                </div>
                                                                                @endauth
                                                                            </div>
                                                                            <div class="reply-content mb-2">
                                                                                {{ $reply->content }}
                                                                            </div>
                                                                            <div class="d-flex align-items-center">
                                                                                @auth
                                                                                <div class="d-flex align-items-center me-3 like-button" data-reply-id="{{ $reply->id }}">
                                                                                    <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1 {{ $reply->likedBy(auth()->user()) ? 'liked' : '' }}">
                                                                                    <span class="likes-count {{ $reply->likedBy(auth()->user()) ? 'active' : '' }}">{{ $reply->likes_count }}</span>
                                                                                </div>
                                                                                <div class="d-flex align-items-center me-3">
                                                                                    <a href="#" class="text-decoration-none text-muted small reply-to-reply-button" data-comment-id="{{ $comment->id }}" data-reply-id="{{ $reply->id }}">Ответить</a>
                                                                                </div>
                                                                                @else
                                                                                <div class="d-flex align-items-center me-3">
                                                                                    <a href="{{ route('login') }}" class="text-decoration-none text-muted small d-flex align-items-center">
                                                                                        <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16" class="me-1">
                                                                                        <span>{{ $reply->likes_count }}</span>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="d-flex align-items-center me-3">
                                                                                    <a href="{{ route('login') }}" class="text-decoration-none text-muted small">Ответить</a>
                                                                                </div>
                                                                                @endauth
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
                                @endforeach
                                @else
                                    <div class="text-center py-3">
                                        <p class="mb-0">Будьте первым, кто оставит комментарий</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
        
        <!-- Правая колонка -->
        <x-right-sidebar :popularTags="$popularTags" :topUsers="$topUsers" :recentAnswers="$recentAnswers ?? null" />
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
            <form action="{{ route('posts.report', $post) }}" method="POST" data-remote="true">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Причина жалобы</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">Выберите причину</option>
                            <option value="spam">Спам</option>
                            <option value="insult">Оскорбление</option>
                            <option value="inappropriate">Неприемлемый контент</option>
                            <option value="copyright">Нарушение авторских прав</option>
                            <option value="other">Другое</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" placeholder="Опишите причину жалобы..."></textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $errors->first('reason') }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отправить жалобу</button>
                </div>
            </form>
            @else
            <div class="modal-body text-center py-4">
                <p class="mb-2">Чтобы отправить жалобу, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a></p>
            </div>
            @endauth
        </div>
    </div>
</div>

<!-- Модальное окно для жалобы на комментарий -->
@foreach($post->comments as $comment)
<div class="modal fade" id="reportCommentModal{{ $comment->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Пожаловаться на комментарий</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('comments.report', $comment) }}" method="POST" data-remote="true">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Тип жалобы</label>
                        <select name="type" class="form-select" required>
                            <option value="">Выберите тип жалобы</option>
                            <option value="spam">Спам</option>
                            <option value="insult">Оскорбление</option>
                            <option value="inappropriate">Неприемлемый контент</option>
                            <option value="copyright">Нарушение авторских прав</option>
                            <option value="other">Другое</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Причина жалобы</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Опишите подробнее причину жалобы..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отправить жалобу</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('styles')
<!-- Стили для страницы поста перенесены в общий файл CSS app.css -->
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Получаем CSRF токен из мета-тега
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Обработка клика по счетчику ответов
    document.querySelectorAll('.replies-count').forEach(counter => {
        counter.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const repliesContainer = document.querySelector(`#replies-${commentId}`);
            
            if (repliesContainer) {
                // Переключаем отображение блока ответов
                if (repliesContainer.style.display === 'none') {
                    repliesContainer.style.display = 'block';
                } else {
                    repliesContainer.style.display = 'none';
                }
            }
        });
    });

    // Находим все формы жалоб
    const reportForms = document.querySelectorAll('form[action*="report"]');
    
    reportForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        // Ошибка валидации
                        return response.json().then(data => {
                            throw new Error(JSON.stringify(data));
                        });
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Закрываем модальное окно
                const modal = this.closest('.modal');
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
                
                // Очищаем форму
                this.reset();
                
                // Показываем сообщение об успехе
                alert('Жалоба успешно отправлена');
            })
            .catch(error => {
                console.error('Error:', error);
                
                try {
                    // Пытаемся распарсить ошибку валидации
                    const validationErrors = JSON.parse(error.message);
                    let errorMessage = 'Ошибка валидации:\n';
                    
                    // Формируем сообщение об ошибке
                    for (const field in validationErrors.errors) {
                        errorMessage += `${validationErrors.errors[field].join('\n')}\n`;
                    }
                    
                    alert(errorMessage);
                } catch (e) {
                    // Если не удалось распарсить JSON, показываем общее сообщение об ошибке
                    alert('Произошла ошибка при отправке жалобы');
                }
            });
        });
    });

    // Обработка лайков
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            @auth
            const postId = this.dataset.postId;
            const commentId = this.dataset.commentId;
            const replyId = this.dataset.replyId;
            
            let url;
            if (postId) {
                url = `/posts/${postId}/like`;
            } else if (commentId) {
                url = `/comments/${commentId}/like`;
            } else if (replyId) {
                url = `/replies/${replyId}/like`;
            } else {
                console.error('Не указан id для лайка');
                return;
            }
            
            const likesCount = this.querySelector('.likes-count');
            const img = this.querySelector('img');
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (likesCount) {
                    // Обновляем текст счетчика лайков
                    likesCount.textContent = data.likes_count;
                    
                    // Добавляем/удаляем класс active для счетчика
                    if (data.liked) {
                        likesCount.classList.add('active');
                    } else {
                        likesCount.classList.remove('active');
                    }
                }
                
                // Добавляем/удаляем класс для изменения цвета иконки
                if (img) {
                    if (data.liked) {
                        img.classList.add('liked');
                    } else {
                        img.classList.remove('liked');
                    }
                }
                
                // Добавляем/удаляем класс active для кнопки
                if (data.liked) {
                    this.classList.add('active');
                } else {
                    this.classList.remove('active');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        });
    });

    // Обработка закладок
    document.querySelector('form[action*="bookmark"]').addEventListener('submit', function(e) {
        e.preventDefault();
        @auth
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const img = this.querySelector('img');
            const button = this.querySelector('button');
            if (img && button) {
                if (data.bookmarked) {
                    img.classList.add('bookmarked');
                    button.classList.add('active');
                } else {
                    img.classList.remove('bookmarked');
                    button.classList.remove('active');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
        @else
        window.location.href = '{{ route("login") }}';
        @endauth
    });

    // Обработка комментариев
    document.querySelector('.comment-toggle').addEventListener('click', function() {
        const commentsSection = document.querySelector('.comments-section');
        commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
        
        // Добавляем/удаляем класс active для кнопки
        this.classList.toggle('active');
        
        // Добавляем/удаляем класс active для изображения
        const img = this.querySelector('img');
        if (img) {
            img.classList.toggle('active');
        }
        
        // Добавляем/удаляем класс active для счетчика
        const span = this.querySelector('span');
        if (span) {
            span.classList.toggle('active');
        }
    });

    // Обработка репоста (копирование ссылки)
    document.getElementById('copy-post-link').addEventListener('click', function() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            // Добавляем класс active для кнопки и элементов
            this.classList.add('active');
            
            // Добавляем класс active для изображения
            const img = this.querySelector('img');
            if (img) {
                img.classList.add('active');
            }
            
            // Добавляем класс active для счетчика
            const span = this.querySelector('span');
            if (span) {
                span.classList.add('active');
            }
            
            // Создаем и показываем сообщение
            const toast = document.createElement('div');
            toast.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
            toast.style.zIndex = '9999';
            toast.textContent = 'Ссылка скопирована в буфер обмена';
            
            document.body.appendChild(toast);
            
            // Удаляем сообщение и класс active через 2 секунды
            setTimeout(() => {
                toast.remove();
                this.classList.remove('active');
                
                // Удаляем класс active для изображения
                if (img) {
                    img.classList.remove('active');
                }
                
                // Удаляем класс active для счетчика
                if (span) {
                    span.classList.remove('active');
                }
            }, 2000);
            
            // Увеличиваем счетчик репостов
            const repostsCount = this.querySelector('span');
            if (repostsCount) {
                repostsCount.textContent = parseInt(repostsCount.textContent) + 1;
            }
        }).catch(err => {
            console.error('Не удалось скопировать: ', err);
        });
    });

    // Обработка ответов на комментарии
    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            @auth
            const commentId = this.dataset.commentId;
            const replyForm = document.querySelector(`#reply-form-${commentId}`);
            replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        });
    });

    // Обработка ответов на ответы
    document.querySelectorAll('.reply-to-reply-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            @auth
            const commentId = this.dataset.commentId;
            const replyId = this.dataset.replyId;
            const replyForm = document.querySelector(`#reply-form-${commentId}`);
            
            // Показываем форму для ответа
            replyForm.style.display = 'block';
            
            // Фокусируемся на текстовом поле
            const textarea = replyForm.querySelector('textarea');
            textarea.focus();
            
            // Добавляем упоминание пользователя, на чей ответ отвечаем
            const replyUserName = this.closest('.comment-reply').querySelector('.user-name').textContent;
            textarea.value = `@${replyUserName} `;
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        });
    });

    // Обработка отмены ответа
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const replyForm = document.querySelector(`#reply-form-${commentId}`);
            replyForm.style.display = 'none';
            
            // Очищаем текстовое поле
            const textarea = replyForm.querySelector('textarea');
            textarea.value = '';
        });
    });
});
</script>
@endpush

@endsection