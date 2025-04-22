@props(['reply'])

<div class="reply p-4 border-b border-gray-200" id="reply-{{ $reply->id }}">
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
        <button class="reply-to-reply-button text-gray-500 hover:text-blue-500 focus:outline-none ml-4" data-reply-id="{{ $reply->id }}">
            <i class="far fa-comment"></i>
            <span>Ответить</span>
        </button>
        
        <button class="text-gray-500 hover:text-red-500 focus:outline-none ml-4" data-bs-toggle="modal" data-bs-target="#reportReplyModal{{ $reply->id }}">
            <i class="far fa-flag"></i>
            <span>Пожаловаться</span>
        </button>
    </div>
    
    <!-- Форма для ответа на ответ -->
    <div id="reply-to-reply-form-{{ $reply->id }}" class="reply-to-reply-form-container mt-4 hidden">
        <form class="reply-to-reply-form" data-reply-id="{{ $reply->id }}">
            <textarea name="content" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="2" placeholder="Напишите ваш ответ..."></textarea>
            <div class="flex justify-end mt-2">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none">
                    Отправить
                </button>
            </div>
        </form>
    </div>

    <!-- Контейнер для ответов на ответ -->
    <div id="replies-{{ $reply->id }}" class="replies-container mt-4">
        @if($reply->replies && $reply->replies->count() > 0)
            @foreach($reply->replies as $replyToReply)
                <x-reply-to-reply :reply="$replyToReply" />
            @endforeach
        @endif
    </div>
</div> 