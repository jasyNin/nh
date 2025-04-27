<div class="post-actions">
    <button class="btn btn-link like-button" data-post-id="{{ $post->id }}">
        <i class="fas fa-heart like-icon {{ $post->likedBy(auth()->user()) ? 'text-danger' : 'text-muted' }}"></i>
        <span class="like-count">{{ $post->likes_count }}</span>
    </button>
</div> 