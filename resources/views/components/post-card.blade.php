@props(['post', 'hideType' => false, 'isShowPage' => false])

<div class="post-card position-relative">
    @if($post->is_hidden)
        <div class="post-hidden-overlay d-flex align-items-center justify-content-center">
            <span class="fw-bold">Пост скрыт модератором</span>
        </div>
    @endif
    <div class="card border-0 @if($post->is_hidden) post-hidden-blur @endif">
        <div class="card-body p-4">
            <!-- Информация о пользователе -->
            <div class="d-flex align-items-center mb-4">
                <div class="d-flex align-items-center flex-grow-1">
                    <div class="position-relative" style="margin-right: 12px !important;">
                        @if($post->user && $post->user->id)
                            <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none">
                                <x-user-avatar :user="$post->user" :size="40" class="me-2" />
                            </a>
                            <x-rank-icon :user="$post->user" />
                        @else
                            <div class="text-muted">
                                <x-user-avatar :user="null" :size="40" class="me-2" />
                            </div>
                        @endif
                    </div>
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center">
                            @if($post->user && $post->user->id)
                                <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none text-dark fw-bold me-2">{{ $post->user->name }}</a>
                            @else
                                <span class="text-muted fw-bold me-2">Удаленный пользователь</span>
                            @endif
                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="d-flex align-items-center">
                            @if($post->user && $post->user->id)
                                <small class="text-muted me-2">{{ $post->user->rank_name }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    @if(!$hideType)
                    <span class="badge bg-{{ $post->type === 'post' ? 'primary' : 'success' }} rounded-pill px-3 py-1 me-2">
                        {{ $post->type === 'post' ? 'Запись' : 'Вопрос' }}
                    </span>
                    @endif
                    <div class="dropdown">
                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->check() && auth()->id() === $post->user_id)
                                <li>
                                    <x-edit-button :targetId="$post->id" />
                                </li>
                                <li>
                                    <x-delete-button :targetId="$post->id" />
                                </li>
                            @elseif(auth()->check() && auth()->id() !== $post->user_id)
                                <li>
                                    <x-report-button :targetId="$post->id" targetType="Post" />
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Заголовок и контент -->
            @if($isShowPage)
                <h1 class="h3 mb-4">{{ $post->title }}</h1>
                <div class="post-content mb-4">
                    {!! $post->content !!}
                </div>
            @else
                <h2 class="h5 mb-3">
                    <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                        {{ $post->title }}
                    </a>
                </h2>
                <div class="post-content mb-3">
                    {!! Str::limit($post->content, 200) !!}
                </div>
            @endif

            <!-- Изображение -->
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
                           class="badge bg-light text-dark text-decoration-none me-2">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Действия с постом -->
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @auth
                    <button class="like-button {{ $post->likedBy(auth()->user()) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
                        <span class="like-icon-wrapper">
                            <svg class="like-icon" width="20" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="likes-count">{{ $post->likes_count }}</span>
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="like-button">
                        <span class="like-icon-wrapper">
                            <svg class="like-icon" width="20" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="likes-count">{{ $post->likes_count }}</span>
                    </a>
                    @endauth

                    <button class="btn btn-link text-dark p-0 me-4 comment-toggle" data-post-id="{{ $post->id }}">
                        <div class="icon-wrapper">
                            <img src="{{ asset('images/comment.svg') }}" alt="Комментарии" width="20" height="19">
                        </div>
                        <span class="ms-1">{{ $post->comments_count }}</span>
                    </button>

                    @auth
                    <button class="btn btn-link text-dark p-0 me-4 share-button {{ $post->repostedBy(auth()->user()) ? 'active' : '' }}" data-post-url="{{ route('posts.show', $post) }}">
                        <div class="icon-wrapper">
                            <img src="{{ asset('images/reply.svg') }}" alt="Поделиться" width="20" height="21" class="{{ $post->repostedBy(auth()->user()) ? 'reposted' : '' }}">
                        </div>
                        <span class="ms-1">{{ $post->reposts_count }}</span>
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 me-4 share-button">
                        <div class="icon-wrapper">
                            <img src="{{ asset('images/reply.svg') }}" alt="Поделиться" width="20" height="21">
                        </div>
                        <span class="ms-1">{{ $post->reposts_count }}</span>
                    </a>
                    @endauth
                </div>

                @auth
                <form action="{{ route('posts.bookmark', $post) }}" method="POST" class="ms-auto">
                    @csrf
                    <button type="submit" class="btn btn-link text-dark p-0 bookmark-button {{ $post->isBookmarkedBy(auth()->user()) ? 'active' : '' }}">
                        <div class="icon-wrapper">
                            <img src="{{ asset('images/bookmark-mini.svg') }}" alt="Закладка" width="20" height="20" class="{{ $post->isBookmarkedBy(auth()->user()) ? 'bookmarked' : '' }}">
                        </div>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 ms-auto">
                    <img src="{{ asset('images/bookmark-mini.svg') }}" alt="Закладка" width="20" height="20">
                </a>
                @endauth
            </div>
        </div>
    </div>
</div>

@if($isShowPage)
    <div class="mt-4">
        <x-comments-section :post="$post" />
    </div>
@else
    <div style="margin-top: -24px;">
        <x-comments-section :post="$post" />
    </div>
@endif

<!-- Модальное окно для жалобы на пост -->
<div class="modal fade" id="reportModal{{ $post->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Пожаловаться на пост</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            @auth
            <form action="{{ route('moderator.complaints.store') }}" method="POST">
                @csrf
                <input type="hidden" name="complaintable_id" value="{{ $post->id }}">
                <input type="hidden" name="complaintable_type" value="App\Models\Post">
                <input type="hidden" name="target_type" value="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Тип жалобы</label>
                        <select name="type" class="form-select" required>
                            <option value="">Выберите причину</option>
                            <option value="spam">Спам</option>
                            <option value="insult">Оскорбление</option>
                            <option value="inappropriate">Неприемлемый контент</option>
                            <option value="copyright">Нарушение авторских прав</option>
                            <option value="violence">Насилие</option>
                            <option value="hate_speech">Разжигание ненависти</option>
                            <option value="fake_news">Фейковые новости</option>
                            <option value="other">Другое</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea name="reason" class="form-control" rows="3" required minlength="10" placeholder="Опишите причину жалобы..."></textarea>
                        <div class="form-text">Минимум 10 символов</div>
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

@once
    @push('styles')
    <style>
        .post-hidden-overlay {
            position: absolute;
            inset: 0;
            z-index: 10;
            background: rgba(255,255,255,0.96);
            color: #1682FD;
            font-size: 1.2rem;
            border-radius: 12px;
            text-align: center;
        }
        .post-hidden-blur {
            filter: blur(1.5px) grayscale(0.2) brightness(0.97);
            pointer-events: none;
            user-select: none;
        }
        .dropdown-menu {
            padding: 11px 12px;
        }
        .dropdown-menu li:not(:last-child) {
            margin-bottom: 12px;
        }
        .dropdown-menu li {
            padding: 0;
        }
        .dropdown-menu li .dropdown-item {
            padding: 0;
        }
    </style>
    @endpush
@endonce 