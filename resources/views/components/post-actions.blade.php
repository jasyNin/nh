@props(['post'])

<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center">
        @auth
        <button class="like-button {{ $post->likedBy(auth()->user()) ? 'active' : '' }}" data-post-id="{{ $post->id }}">
            <svg class="like-icon" width="20" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="likes-count">{{ $post->likes_count }}</span>
        </button>
        @else
        <a href="{{ route('login') }}" class="like-button">
            <svg class="like-icon" width="20" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="likes-count">{{ $post->likes_count }}</span>
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

@push('styles')
<link href="{{ asset('css/post-actions.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/post-actions.js') }}"></script>
@endpush 