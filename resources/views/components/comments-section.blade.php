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
                    @if($comment->user && $comment->user->id)
                        <a href="{{ route('users.show', $comment->user) }}">
                            <div class="position-relative">
                                <x-user-avatar :user="$comment->user" :size="40" />
                                <x-rank-icon :user="$comment->user" />
                            </div>
                        </a>
                    @else
                        <div class="position-relative">
                            <x-user-avatar :user="null" :size="40" />
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <div>
                            @if($comment->user && $comment->user->id)
                                <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                                <span class="text-muted ms-2 small">{{ $comment->created_at->diffForHumans() }}</span>
                                <div class="text-muted small">{{ $comment->user->rank_name }}</div>
                            @else
                                <span class="text-muted fw-bold">Удаленный пользователь</span>
                                <span class="text-muted ms-2 small">{{ $comment->created_at->diffForHumans() }}</span>
                            @endif
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
                            <button class="btn btn-link p-0 me-3 comment-like-button" data-comment-id="{{ $comment->id }}" data-post-id="{{ $comment->post_id }}">
                                <div class="like-wrapper">
                                    <div class="like-icon-wrapper">
                                        <img src="{{ asset($comment->likedBy(auth()->user()) ? 'images/like-filled.svg' : 'images/like.svg') }}" alt="Лайк" class="like-icon" width="18" height="16">
                                    </div>
                                    <span class="like-count {{ $comment->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $comment->likes_count }}</span>
                                </div>
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
                            <a href="{{ route('login') }}" class="btn btn-link p-0 me-3">
                                <div class="like-wrapper">
                                    <div class="like-icon-wrapper">
                                        <img src="{{ asset('images/like.svg') }}" alt="Лайк" class="like-icon" width="18" height="16">
                                    </div>
                                    <span class="like-count">{{ $comment->likes_count }}</span>
                                </div>
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
                                        @if($reply->user && $reply->user->id)
                                            <a href="{{ route('users.show', $reply->user) }}">
                                                <div class="position-relative">
                                                    <x-user-avatar :user="$reply->user" :size="32" />
                                                    <x-rank-icon :user="$reply->user" />
                                                </div>
                                            </a>
                                        @else
                                            <div class="position-relative">
                                                <x-user-avatar :user="null" :size="32" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                @if($reply->user && $reply->user->id)
                                                    <a href="{{ route('users.show', $reply->user) }}" class="text-decoration-none text-dark fw-bold">{{ $reply->user->name }}</a>
                                                    <span class="text-muted ms-2 small">{{ $reply->created_at->diffForHumans() }}</span>
                                                    <div class="text-muted small">{{ $reply->user->rank_name }}</div>
                                                @else
                                                    <span class="text-muted fw-bold">Удаленный пользователь</span>
                                                    <span class="text-muted ms-2 small">{{ $reply->created_at->diffForHumans() }}</span>
                                                @endif
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
                                                <button class="btn btn-link p-0 me-3 reply-like-button" data-reply-id="{{ $reply->id }}" data-post-id="{{ $comment->post_id }}">
                                                    <div class="like-wrapper">
                                                        <div class="like-icon-wrapper">
                                                            <img src="{{ asset($reply->likedBy(auth()->user()) ? 'images/like-filled.svg' : 'images/like.svg') }}" alt="Лайк" class="like-icon" width="18" height="16">
                                                        </div>
                                                        <span class="like-count {{ $reply->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $reply->likes_count }}</span>
                                                    </div>
                                                </button>
                                                <button class="btn btn-link text-muted p-0 reply-button" data-comment-id="{{ $reply->id }}">
                                                    Ответить
                                                </button>
                                                @else
                                                <a href="{{ route('login') }}" class="btn btn-link p-0 me-3">
                                                    <div class="like-wrapper">
                                                        <div class="like-icon-wrapper">
                                                            <img src="{{ asset('images/like.svg') }}" alt="Лайк" class="like-icon" width="18" height="16">
                                                        </div>
                                                        <span class="like-count">{{ $reply->likes_count }}</span>
                                                    </div>
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

<style>
.like-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.like-icon-wrapper {
    padding: 4px;
    border-radius: 14px;
    transition: background-color 0.2s;
}

.like-icon-wrapper:hover {
    background-color: #FEF1F3;
}

.like-icon {
    transition: all 0.2s;
}

.like-icon.liked {
    filter: invert(37%) sepia(74%) saturate(1352%) hue-rotate(314deg) brightness(91%) contrast(101%);
}

.like-icon.liked path {
    fill: #E65C77;
}

.like-count {
    font-size: 14px;
    color: #595959;
    transition: color 0.2s;
}

.like-count.liked {
    color: #E65C77;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Обработка лайков комментариев
    const commentLikeButtons = document.querySelectorAll('.comment-like-button');
    commentLikeButtons.forEach(button => {
        button.addEventListener('click', async function() {
            if (this.disabled) return;
            this.disabled = true;
            
            const commentId = this.dataset.commentId;
            const postId = this.dataset.postId;
            const likeIcon = this.querySelector('.like-icon');
            const likeCount = this.querySelector('.like-count');
            
            try {
                const response = await fetch(`/comments/${commentId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ post_id: postId })
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.error || 'Something went wrong');
                }
                
                if (data.liked) {
                    likeIcon.src = "{{ asset('images/like-filled.svg') }}";
                    likeCount.classList.add('liked');
                } else {
                    likeIcon.src = "{{ asset('images/like.svg') }}";
                    likeCount.classList.remove('liked');
                }
                
                likeCount.textContent = data.likes_count;
            } catch (error) {
                console.error('Error:', error);
                const currentCount = parseInt(likeCount.textContent);
                likeCount.textContent = data.liked ? currentCount - 1 : currentCount + 1;
                likeIcon.src = data.liked ? "{{ asset('images/like.svg') }}" : "{{ asset('images/like-filled.svg') }}";
                likeCount.classList.toggle('liked');
            } finally {
                setTimeout(() => {
                    this.disabled = false;
                }, 500);
            }
        });
    });

    // Обработка лайков ответов
    const replyLikeButtons = document.querySelectorAll('.reply-like-button');
    replyLikeButtons.forEach(button => {
        button.addEventListener('click', async function() {
            if (this.disabled) return;
            this.disabled = true;
            
            const replyId = this.dataset.replyId;
            const postId = this.dataset.postId;
            const likeIcon = this.querySelector('.like-icon');
            const likeCount = this.querySelector('.like-count');
            
            try {
                const response = await fetch(`/replies/${replyId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ post_id: postId })
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.error || 'Something went wrong');
                }
                
                if (data.liked) {
                    likeIcon.src = "{{ asset('images/like-filled.svg') }}";
                    likeCount.classList.add('liked');
                } else {
                    likeIcon.src = "{{ asset('images/like.svg') }}";
                    likeCount.classList.remove('liked');
                }
                
                likeCount.textContent = data.likes_count;
            } catch (error) {
                console.error('Error:', error);
                const currentCount = parseInt(likeCount.textContent);
                likeCount.textContent = data.liked ? currentCount - 1 : currentCount + 1;
                likeIcon.src = data.liked ? "{{ asset('images/like.svg') }}" : "{{ asset('images/like-filled.svg') }}";
                likeCount.classList.toggle('liked');
            } finally {
                setTimeout(() => {
                    this.disabled = false;
                }, 500);
            }
        });
    });
});
</script> 