@props(['viewedPosts', 'popularTags', 'topUsers', 'recentAnswers'])

<div class="col-md-3 right-sidebar" style="margin-top: 20px;">
    @auth
        @if($viewedPosts->isNotEmpty())
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="card-title fw-bold mb-0">История просмотров</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($viewedPosts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action border-0 py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="list-title text-truncate me-3">{{ $post->title }}</div>
                                <small class="text-muted">{{ $post->type === 'post' ? 'Запись' : 'Вопрос' }}</small>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endauth

    @if(count($popularTags) > 0)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="card-title fw-bold mb-0">Популярные теги</h6>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($popularTags->take(6) as $tag)
                        <a href="{{ route('tags.show', $tag) }}" class="tag-badge">
                            #{{ $tag->name }}
                            <span class="tag-count">{{ $tag->posts_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if(count($topUsers) > 0)
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="card-title fw-bold mb-0">Топ пользователей</h6>
            </div>
            <div class="list-group list-group-flush">
                @foreach($topUsers->take(3) as $user)
                    <a href="{{ route('users.show', $user) }}" class="list-group-item list-group-item-action border-0 py-3">
                        <div class="d-flex align-items-center">
                            <x-user-avatar :user="$user" :size="40" class="me-3" style="margin-right: 12px !important;" />
                            <div style="margin-left: 12px;">
                                <div class="user-name fw-bold">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->rating ?? $user->posts_count }} {{ isset($user->rating) ? __('rating.points') : pluralize($user->posts_count, 'пост', 'поста', 'постов') }}</small>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if(count($recentAnswers) > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="card-title fw-bold mb-0">Последние ответы</h6>
            </div>
            <div class="list-group list-group-flush">
                @foreach($recentAnswers->take(3) as $answer)
                    <a href="{{ route('posts.show', $answer->post) }}" class="list-group-item list-group-item-action border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="list-title">{{ Str::limit($answer->post->title, 40) }}</div>
                            <small class="text-muted">{{ $answer->created_at->diffForHumans() }}</small>
                        </div>
                        <small class="text-muted d-block">{{ $answer->user->name }}</small>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div> 