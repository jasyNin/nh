<div class="post-comments">
    <button class="toggle-comments-btn" data-post-id="{{ $post->id }}">
        Показать комментарии
    </button>
    
    <div class="comments-section" data-post-id="{{ $post->id }}">
        @foreach($post->comments as $comment)
            <div class="comment">
                <div class="comment-content">
                    {{ $comment->content }}
                </div>
                <div class="comment-meta">
                    <span class="comment-author">{{ $comment->user->name }}</span>
                    <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                    
                    @if($comment->replies->count() > 0)
                        <button class="toggle-replies-btn" data-comment-id="{{ $comment->id }}">
                            Показать ответы
                        </button>
                        
                        <div class="replies-container" data-comment-id="{{ $comment->id }}">
                            @foreach($comment->replies as $reply)
                                <div class="reply">
                                    <div class="reply-content">
                                        {{ $reply->content }}
                                    </div>
                                    <div class="reply-meta">
                                        <span class="reply-author">{{ $reply->user->name }}</span>
                                        <span class="reply-date">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div> 