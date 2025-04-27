@props(['popularTags' => [], 'topUsers' => [], 'recentAnswers' => [], 'isTagsPage' => false, 'isHomePage' => false, 'userStats' => null, 'viewedPosts' => []])

<div class="col-md-3 right-sidebar" style="margin-top: 20px;">
    @if($isTagsPage)
        <div class="card mb-4 border-0">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="card-title">Популярные теги</h6>
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

        @if(count($recentAnswers) > 0)
            <div class="card border-0">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="card-title">Последние ответы</h6>
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
    @else
        @auth
            @if($isHomePage)
                @if($viewedPosts->isNotEmpty())
                    <div class="card mb-4 border-0">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h6 class="card-title">История просмотров</h6>
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
            @endif
        @endauth

        @if($userStats)
            <div class="card mb-4 border-0">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="card-title">Статистика</h6>
                </div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                <span>Постов</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $userStats['posts_count'] }}</span>
                        </div>
                    </div>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-comments text-primary me-2"></i>
                                <span>Комментариев</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $userStats['comments_count'] }}</span>
                        </div>
                    </div>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-heart text-danger me-2"></i>
                                <span>Лайков</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $userStats['likes_received'] }}</span>
                        </div>
                    </div>
                    <div class="list-group-item border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-bookmark text-primary me-2"></i>
                                <span>Закладок</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $userStats['bookmarks_count'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(count($popularTags) > 0)
            <div class="card mb-4 border-0">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="card-title">Популярные теги</h6>
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
            <div class="card mb-4 border-0">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="card-title">Топ пользователей</h6>
                </div>
                <div class="list-group list-group-flush">
                    @php
                        $regularTopUsers = App\Models\User::query()
                            ->whereNotIn('rank', ['bot', 'moderator', 'admin'])
                            ->orderByRaw("CASE rank 
                                WHEN 'supermind' THEN 1
                                WHEN 'master' THEN 2
                                WHEN 'erudite' THEN 3
                                WHEN 'expert' THEN 4
                                WHEN 'student' THEN 5
                                WHEN 'novice' THEN 6
                                ELSE 7 END")
                            ->orderBy('rating', 'desc')
                            ->take(3)
                            ->get();
                    @endphp
                    @foreach($regularTopUsers as $user)
                        <a href="{{ route('users.show', $user) }}" class="list-group-item list-group-item-action border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    <x-user-avatar :user="$user" :size="40" class="me-3" style="margin-right: 12px !important;" />
                                    <x-rank-icon :user="$user" />
                                </div>
                                <div style="margin-left: 12px;">
                                    <div class="user-name fw-bold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->rank_name }}</small>
                                    <small class="text-muted d-block">{{ $user->rating }} баллов</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if(count($recentAnswers) > 0)
            <div class="card border-0">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="card-title">Последние ответы</h6>
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
    @endif
</div> 