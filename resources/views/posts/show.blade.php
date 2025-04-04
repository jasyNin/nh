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
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="{{ $post->likedBy(auth()->user()) ? 'currentColor' : 'none' }}">
                                    <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5C22 12.28 18.6 15.36 13.45 20.04L12 21.35Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="ms-1 likes-count" style="pointer-events: none;">{{ $post->likes_count }}</span>
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 me-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5C22 12.28 18.6 15.36 13.45 20.04L12 21.35Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="ms-1">{{ $post->likes_count }}</span>
                            </a>
                            @endauth

                            <button class="btn btn-link text-dark p-0 me-4 comment-toggle">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="ms-1">{{ $post->comments_count }}</span>
                            </button>

                            <button class="btn btn-link text-dark p-0 me-4">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M17 1l4 4-4 4"></path>
                                    <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                    <path d="M7 23l-4-4 4-4"></path>
                                    <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                </svg>
                                <span class="ms-1">{{ $post->reposts_count }}</span>
                            </button>
                        </div>

                        @auth
                        <form action="{{ route('posts.bookmark', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-dark p-0">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="{{ $post->isBookmarkedBy(auth()->user()) ? 'currentColor' : 'none' }}">
                                    <path d="M5 5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21L12 16L5 21V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-link text-dark p-0">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M5 5C5 4.46957 5.21071 3.96086 5.58579 3.58579C5.96086 3.21071 6.46957 3 7 3H17C17.5304 3 18.0391 3.21071 18.4142 3.58579C18.7893 3.96086 19 4.46957 19 5V21L12 16L5 21V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        @endauth
                    </div>

                    <!-- Комментарии -->
                    <div class="comments-section">
                        @auth
                            <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" placeholder="Напишите комментарий...">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $errors->first('content') }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <p class="mb-2">Чтобы оставить комментарий, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a></p>
                            </div>
                        @endauth

                        @if($post->comments->isNotEmpty())
                            <div class="comments-list">
                                @foreach($post->comments as $comment)
                                    <div class="comment mb-4">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <a href="{{ route('users.show', $comment->user) }}">
                                                    <x-user-avatar :user="$comment->user" :size="40" />
                                                </a>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                                                        <span class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    @auth
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
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
                                                    <button class="btn btn-link text-dark p-0 me-3 like-button" data-comment-id="{{ $comment->id }}">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="{{ $comment->likedBy(auth()->user()) ? 'currentColor' : 'none' }}">
                                                            <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5C22 12.28 18.6 15.36 13.45 20.04L12 21.35Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        <span class="ms-1 likes-count" style="pointer-events: none;">{{ $comment->likes_count }}</span>
                                                    </button>
                                                    <button class="btn btn-link text-dark p-0 reply-button" data-comment-id="{{ $comment->id }}">
                                                        Ответить
                                                    </button>
                                                    @else
                                                    <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 me-3">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                            <path d="M12 21.35L10.55 20.03C5.4 15.36 2 12.28 2 8.5C2 5.42 4.42 3 7.5 3C9.24 3 10.91 3.81 12 5.09C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.42 22 8.5C22 12.28 18.6 15.36 13.45 20.04L12 21.35Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        <span class="ms-1">{{ $comment->likes_count }}</span>
                                                    </a>
                                                    <a href="{{ route('login') }}" class="btn btn-link text-dark p-0">
                                                        Ответить
                                                    </a>
                                                    @endauth
                                                </div>

                                                <!-- Форма для ответа -->
                                                <div class="reply-form mt-3" style="display: none;" id="reply-form-{{ $comment->id }}">
                                                    <form action="{{ route('comments.replies.store', $comment) }}" method="POST" class="reply-form">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <textarea name="content" class="form-control" rows="2" placeholder="Напишите ответ..."></textarea>
                                                        </div>
                                                        <div class="d-flex justify-content-end">
                                                            <button type="button" class="btn btn-link text-dark me-2 cancel-reply" data-comment-id="{{ $comment->id }}">Отмена</button>
                                                            <button type="submit" class="btn btn-primary">Отправить</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- Ответы на комментарий -->
                                                @if($comment->replies->isNotEmpty())
                                                    <div class="replies mt-3">
                                                        @foreach($comment->replies as $reply)
                                                            <x-comment-reply :reply="$reply" />
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
    
    // Остальной JavaScript код...

    // Обработка лайков
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            @auth
            const postId = this.dataset.postId;
            const commentId = this.dataset.commentId;
            const url = postId ? `/posts/${postId}/like` : `/comments/${commentId}/like`;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const likesCount = this.querySelector('.likes-count');
                if (likesCount) {
                    likesCount.textContent = data.rating;
                }
                
                const svg = this.querySelector('svg');
                if (svg) {
                    svg.setAttribute('fill', data.liked ? 'currentColor' : 'none');
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
            const svg = this.querySelector('svg');
            if (svg) {
                svg.setAttribute('fill', data.bookmarked ? 'currentColor' : 'none');
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
    });

    // Обработка ответов на комментарии
    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', function() {
            @auth
            const commentId = this.dataset.commentId;
            const replyForm = document.querySelector(`#reply-form-${commentId}`);
            replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
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
        });
    });

    // Обработка жалоб
    document.querySelectorAll('form[action*="report"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            @auth
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.querySelector('#reportModal'));
                    modal.hide();
                    alert('Жалоба успешно отправлена');
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
});
</script>
@endpush
@endsection