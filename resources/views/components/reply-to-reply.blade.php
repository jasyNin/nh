@props(['reply'])

<div class="reply-to-reply p-4 ml-8 border-l-2 border-gray-200" id="reply-to-reply-{{ $reply->id }}">
    <div class="flex items-center mb-2">
        <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-8 h-8 rounded-full mr-2">
        <span class="font-semibold">{{ $reply->user->name }}</span>
        <span class="text-gray-500 text-sm ml-2">{{ $reply->created_at->diffForHumans() }}</span>
    </div>
    <p class="text-gray-800">{{ $reply->content }}</p>
    <div class="flex items-center mt-2">
        <button class="like-button text-gray-500 hover:text-pink-500 focus:outline-none {{ auth()->check() && $reply->likedBy(auth()->user()) ? 'text-pink-500' : '' }}" data-id="{{ $reply->id }}">
            <i class="far fa-heart"></i>
            <span class="like-count">{{ $reply->likes()->count() }}</span>
        </button>
    </div>
</div> 