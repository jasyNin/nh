@props(['post'])

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

        <button class="btn btn-link text-dark p-0 me-4 comment-toggle" data-post-id="{{ $post->id }}">
            <img src="{{ asset('images/comment.svg') }}" alt="Комментарии" width="20" height="19">
            <span class="ms-1">{{ $post->comments_count }} {{ pluralize($post->comments_count, 'комментарий', 'комментария', 'комментариев') }}</span>
        </button>

        @auth
        <button class="btn btn-link text-dark p-0 me-4 repost-button" data-post-id="{{ $post->id }}">
            <img src="{{ asset('images/reply.svg') }}" alt="Поделиться" width="20" height="21">
            <span class="ms-1">{{ $post->reposts_count }}</span>
        </button>
        @else
        <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 me-4">
            <img src="{{ asset('images/reply.svg') }}" alt="Поделиться" width="20" height="21">
            <span class="ms-1">{{ $post->reposts_count }}</span>
        </a>
        @endauth
    </div>

    @auth
    <form action="{{ route('posts.bookmark', $post) }}" method="POST" class="ms-auto">
        @csrf
        <button type="submit" class="btn btn-link text-dark p-0 bookmark-button {{ $post->isBookmarkedBy(auth()->user()) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
            <img src="{{ asset('images/bookmark-mini.svg') }}" alt="Закладка" width="20" height="20" class="{{ $post->isBookmarkedBy(auth()->user()) ? 'bookmarked' : '' }}">
        </button>
    </form>
    @else
    <a href="{{ route('login') }}" class="btn btn-link text-dark p-0 ms-auto">
        <img src="{{ asset('images/bookmark-mini.svg') }}" alt="Закладка" width="20" height="20">
    </a>
    @endauth
</div> 