@props(['popularTags' => [], 'topUsers' => [], 'recentAnswers' => [], 'isTagsPage' => false, 'isHomePage' => false, 'userStats' => null, 'viewedPosts' => [], 'users' => []])

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

        @if(isset($users) && count($users) > 0)
            @php
                $regularUsers = $users->filter(function($user) {
                    return !in_array($user->rank, ['bot', 'moderator', 'admin']);
                })->sortBy(function($user) {
                    $rankOrder = [
                        'supermind' => 1,
                        'master' => 2,
                        'erudite' => 3,
                        'expert' => 4,
                        'student' => 5,
                        'novice' => 6
                    ];
                    return [$rankOrder[$user->rank] ?? 999, -$user->rating];
                });

                $specialUsers = $users->filter(function($user) {
                    return in_array($user->rank, ['bot', 'moderator', 'admin']);
                })->sortByDesc('rating');

                $sortedUsers = $regularUsers->concat($specialUsers);
            @endphp
            <div class="card mb-4 border-0">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="card-title">Топ пользователей</h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($sortedUsers->take(3) as $user)
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