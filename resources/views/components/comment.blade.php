@props(['comment'])

<div class="comment" id="comment-{{ $comment->id }}">
    <div class="d-flex">
        <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none me-2">
            <x-user-avatar :user="$comment->user" :size="32" />
        </a>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center">
                <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none text-dark fw-bold me-2">{{ $comment->user->name }}</a>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
            <div class="comment-content">{{ $comment->content }}</div>
            
            <div class="d-flex align-items-center mt-2">
                @auth
                <button class="btn btn-link p-0 me-3 comment-like-button" data-comment-id="{{ $comment->id }}" data-post-id="{{ $comment->post_id }}">
                    <div class="like-wrapper">
                        <img src="{{ asset('images/like.svg') }}" alt="Лайк" class="like-icon {{ $comment->likedBy(auth()->user()) ? 'liked' : '' }}" width="18" height="16">
                        <span class="like-count {{ $comment->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $comment->likes_count }}</span>
                    </div>
                </button>
                @else
                <a href="{{ route('login') }}" class="btn btn-link p-0 me-3">
                    <div class="like-wrapper">
                        <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16">
                        <span class="like-count">{{ $comment->likes_count }}</span>
                    </div>
                </a>
                @endauth
                
                <div class="replies-toggle" data-comment-id="{{ $comment->id }}">
                    {{ $comment->replies_count }} {{ pluralize($comment->replies_count, 'ответ', 'ответа', 'ответов') }}
                </div>
                
                @auth
                <button class="btn btn-link text-dark p-0 ms-2 reply-button" data-comment-id="{{ $comment->id }}">
                    Ответить
                </button>
                
                <button class="btn btn-link text-dark p-0 ms-2" data-bs-toggle="modal" data-bs-target="#reportCommentModal{{ $comment->id }}">
                    Пожаловаться
                </button>
                @endauth
            </div>
            
            <!-- Форма ответа на комментарий -->
            <div class="reply-form-container" id="reply-form-{{ $comment->id }}">
                <form action="{{ route('comments.replies.store', $comment) }}" method="POST" class="reply-form">
                    @csrf
                    <div class="input-group">
                        <textarea name="content" class="form-control reply-textarea" rows="1" placeholder="Ответить..."></textarea>
                        <button type="submit" class="btn btn-primary reply-submit-btn">
                            Отправить
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Ответы на комментарий -->
            <div class="replies-container" id="replies-{{ $comment->id }}">
                @if($comment->replies && $comment->replies->count() > 0)
                    @foreach($comment->replies->take(3) as $reply)
                        <x-reply :reply="$reply" />
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.like-wrapper {
    position: relative;
    display: inline-flex;
    align-items: center;
    padding: 4px;
    border-radius: 14px;
    transition: background-color 0.2s;
}

.like-wrapper:hover {
    background-color: #FEF1F3;
}

.like-wrapper:hover .like-icon,
.like-wrapper:hover .like-count {
    color: #E65C77;
}

.like-icon {
    transition: all 0.2s;
}

.like-icon.liked {
    filter: invert(37%) sepia(74%) saturate(1352%) hue-rotate(314deg) brightness(91%) contrast(101%);
}

.like-count {
    margin-left: 4px;
    font-size: 14px;
    color: #595959;
    transition: color 0.2s;
}

.like-count.liked {
    color: #E65C77;
}
</style> 