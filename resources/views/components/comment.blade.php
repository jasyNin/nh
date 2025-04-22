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
                <div class="d-flex align-items-center me-3 like-button" data-comment-id="{{ $comment->id }}">
                    <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="16" height="14" class="me-1 {{ auth()->check() && $comment->likedBy(auth()->user()) ? 'liked' : '' }}">
                    <span class="likes-count {{ auth()->check() && $comment->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $comment->likes_count }}</span>
                </div>
                
                <div class="replies-toggle" data-comment-id="{{ $comment->id }}">
                    {{ $comment->replies_count }} {{ pluralize($comment->replies_count, 'ответ', 'ответа', 'ответов') }}
                </div>
                
                <button class="btn btn-link text-dark p-0 ms-2 reply-button" data-comment-id="{{ $comment->id }}">
                    Ответить
                </button>
                
                <button class="btn btn-link text-dark p-0 ms-2" data-bs-toggle="modal" data-bs-target="#reportCommentModal{{ $comment->id }}">
                    Пожаловаться
                </button>
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