@props(['reply'])

<div class="reply-to-reply p-4 ml-8 border-l-2 border-gray-200" id="reply-to-reply-{{ $reply->id }}">
    <div class="flex items-center mb-2">
        <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-8 h-8 rounded-full mr-2">
        <span class="font-semibold">{{ $reply->user->name }}</span>
        <span class="text-gray-500 text-sm ml-2">{{ $reply->created_at->diffForHumans() }}</span>
    </div>
    <p class="text-gray-800">{{ $reply->content }}</p>
    <div class="flex items-center mt-2">
        @auth
        <button class="btn btn-link p-0 me-3 reply-like-button" data-reply-id="{{ $reply->id }}" data-post-id="{{ $reply->comment->post_id }}">
            <div class="like-wrapper">
                <img src="{{ asset('images/like.svg') }}" alt="Лайк" class="like-icon {{ $reply->likedBy(auth()->user()) ? 'liked' : '' }}" width="18" height="16">
                <span class="like-count {{ $reply->likedBy(auth()->user()) ? 'liked' : '' }}">{{ $reply->likes_count }}</span>
            </div>
        </button>
        @else
        <a href="{{ route('login') }}" class="btn btn-link p-0 me-3">
            <div class="like-wrapper">
                <img src="{{ asset('images/like.svg') }}" alt="Лайк" width="18" height="16">
                <span class="like-count">{{ $reply->likes_count }}</span>
            </div>
        </a>
        @endauth
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