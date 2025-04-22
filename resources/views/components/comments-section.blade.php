@props(['post'])

<div class="comments-section mt-3" style="display: none;" id="comments-section-{{ $post->id }}">
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
                            <button class="btn btn-link text-muted p-0 me-3 like-button" data-comment-id="{{ $comment->id }}">
                                <i class="bi bi-heart{{ $comment->likedBy(auth()->user()) ? '-fill text-danger' : '' }}"></i>
                                <span class="ms-1">{{ $comment->likes_count }}</span>
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
                                                <button class="btn btn-link text-muted p-0 me-3 like-button" data-comment-id="{{ $reply->id }}">
                                                    <i class="bi bi-heart{{ $reply->likedBy(auth()->user()) ? '-fill text-danger' : '' }}"></i>
                                                    <span class="ms-1">{{ $reply->likes_count }}</span>
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