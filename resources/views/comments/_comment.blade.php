<div class="comment-actions">
    <button class="btn btn-link comment-like-button" data-comment-id="{{ $comment->id }}">
        <i class="fas fa-heart like-icon {{ $comment->likedBy(auth()->user()) ? 'text-danger' : 'text-muted' }}"></i>
        <span class="like-count">{{ $comment->likes()->count() }}</span>
    </button>
</div> 