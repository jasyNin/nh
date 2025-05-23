@extends('layouts.app')

@section('title', 'Р“Р»Р°РІРЅР°СЏ')

@section('content')
<div class="container" style="margin-top: 60px;">
    <div class="row">
        <!-- Р‘РѕРєРѕРІРѕРµ РјРµРЅСЋ -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- РћСЃРЅРѕРІРЅРѕР№ РєРѕРЅС‚РµРЅС‚ -->
        <div class="col-md-7">
            <div class="card border-0 bg-transparent">
                <div class="card-header bg-transparent border-0 ">
                    <ul class="nav nav-tabs card-header-tabs border-0"  style="margin-top: 15px;">
                        <li class="nav-item" >
                            <a class="nav-link {{ !request('type') ? 'active' : '' }}" href="{{ route('home') }}">
                                Р’СЃРµ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('type') === 'post' ? 'active' : '' }}" href="{{ route('home', ['type' => 'post']) }}">
                                Р—Р°РїРёСЃРё
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('type') === 'question' ? 'active' : '' }}" href="{{ route('home', ['type' => 'question']) }}">
                                Р’РѕРїСЂРѕСЃС‹
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if($posts->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/no-posts.svg') }}" alt="РџРѕСЃС‚РѕРІ РїРѕРєР° РЅРµС‚" width="48" height="48" class="mb-3">
                            <h5 class="fw-light mb-3">РџРѕСЃС‚РѕРІ РїРѕРєР° РЅРµС‚</h5>
                            <p class="text-muted mb-4">РЎРѕР·РґР°Р№С‚Рµ СЃРІРѕР№ РїРµСЂРІС‹Р№ РїРѕСЃС‚, С‡С‚РѕР±С‹ РЅР°С‡Р°С‚СЊ</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
                                РЎРѕР·РґР°С‚СЊ РїРѕСЃС‚
                            </a>
                        </div>
                    @else
                        <div class="posts-container">
                            @foreach($posts as $post)
                                <div class="post-card">
                                    <div class="card border-0 hover-card">
                                        <div class="card-body p-4">
                                            <!-- РРЅС„РѕСЂРјР°С†РёСЏ Рѕ РїРѕР»СЊР·РѕРІР°С‚РµР»Рµ -->
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="me-2">
                                                    <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none">
                                                        <x-user-avatar :user="$post->user" :size="52" />
                                                    </a>
                                                </div>
                                                <div>
                                                    <a href="{{ route('users.show', $post->user) }}" class="text-decoration-none text-dark">
                                                        <h6 class="mb-0">{{ $post->user->name }}</h6>
                                                    </a>
                                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                                </div>
                                                <div class="ms-auto d-flex align-items-center">
                                                    <span class="badge bg-{{ $post->type === 'post' ? 'primary' : 'success' }} rounded-pill px-3 py-1 me-2">
                                                        {{ $post->type === 'post' ? 'Р—Р°РїРёСЃСЊ' : 'Р’РѕРїСЂРѕСЃ' }}
                                                    </span>
                                                    
                                                    <!-- Р”РѕР±Р°РІР»СЏРµРј РјРµРЅСЋ СѓРїСЂР°РІР»РµРЅРёСЏ -->
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                                <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#595959" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#595959" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#595959" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            @if(auth()->check() && auth()->id() === $post->user_id)
                                                                <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ</a></li>
                                                                <li>
                                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Р’С‹ СѓРІРµСЂРµРЅС‹, С‡С‚Рѕ С…РѕС‚РёС‚Рµ СѓРґР°Р»РёС‚СЊ СЌС‚РѕС‚ РїРѕСЃС‚?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger">РЈРґР°Р»РёС‚СЊ</button>
                                                                    </form>
                                                                </li>
                                                            @else
                                                                <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#reportPostModal{{ $post->id }}">РџРѕР¶Р°Р»РѕРІР°С‚СЊСЃСЏ</a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Р—Р°РіРѕР»РѕРІРѕРє Рё РєРѕРЅС‚РµРЅС‚ -->
                                            <div class="post-content">
                                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                                    <h5 class="card-title mb-3 text-dark">
                                                        {{ $post->title }}
                                                    </h5>
                                                    
                                                    <p class="card-text text-muted mb-3">{{ Str::limit($post->content, 200) }}</p>

                                                    <!-- РР·РѕР±СЂР°Р¶РµРЅРёРµ, РµСЃР»Рё РµСЃС‚СЊ -->
                                                    @if($post->image)
                                                        <div class="post-image mb-3">
                                                            <img src="{{ asset('storage/' . $post->image) }}" 
                                                                 class="img-fluid rounded" 
                                                                 alt="{{ $post->title }}">
                                                        </div>
                                                    @endif

                                                    <!-- РўРµРіРё -->
                                                    @if($post->tags->isNotEmpty())
                                                        <div class="tags mb-3">
                                                            @foreach($post->tags as $tag)
                                                                <a href="{{ route('tags.show', $tag) }}" 
                                                                   class="badge bg-light text-dark text-decoration-none me-1">
                                                                    #{{ $tag->name }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </a>
                                            </div>

                                            <!-- РЎС‚Р°С‚РёСЃС‚РёРєР° -->
                                            <div class="d-flex align-items-center text-muted">
                                                <div class="d-flex align-items-center me-4 like-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/like.svg') }}" alt="Р›Р°Р№Рє" width="18" height="16" class="me-1">
                                                    <span class="likes-count" style="pointer-events: none;">{{ $post->likes_count }}</span>
                                                </div>
                                                <div class="d-flex align-items-center me-4 comment-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/comment.svg') }}" alt="РљРѕРјРјРµРЅС‚Р°СЂРёРё" width="20" height="19" class="me-1">
                                                    <span class="comments-count">{{ $post->comments_count }}</span>
                                                </div>
                                                <div class="d-flex align-items-center me-4 repost-button">
                                                    <img src="{{ asset('images/reply.svg') }}" alt="РџРѕРґРµР»РёС‚СЊСЃСЏ" width="20" height="21" class="me-1">
                                                    <span class="reposts-count">{{ $post->reposts_count }}</span>
                                                </div>
                                                <div class="ms-auto d-flex align-items-center bookmark-button" data-post-id="{{ $post->id }}">
                                                    <img src="{{ asset('images/bookmark-mini.svg') }}" alt="Р—Р°РєР»Р°РґРєР°" width="20" height="20" class="me-1">
                                                </div>
                                            </div>

                                            <!-- Р Р°Р·РґРµР» РєРѕРјРјРµРЅС‚Р°СЂРёРµРІ (СЃРєСЂС‹С‚ РїРѕ СѓРјРѕР»С‡Р°РЅРёСЋ) -->
                                            <div id="comments-container-{{ $post->id }}" class="comments-container mt-3 border-top pt-3" style="display: none;">
                                                <!-- РћС‚РѕР±СЂР°Р¶РµРЅРёРµ РѕС‚РІРµС‚РѕРІ РІ РєРѕРЅС‚РµР№РЅРµСЂРµ РєРѕРјРјРµРЅС‚Р°СЂРёРµРІ -->
                                                @if($post->type === 'question' && $post->answers->isNotEmpty())
                                                    <div class="answers-section mb-4">
                                                        <h6 class="small fw-bold mb-3">{{ $post->answers_count > 1 ? 'РћС‚РІРµС‚С‹ РЅР° РІРѕРїСЂРѕСЃ:' : 'РћС‚РІРµС‚ РЅР° РІРѕРїСЂРѕСЃ:' }}</h6>
                                                        <div class="answers-list">
                                                            @foreach($post->answers->take(2) as $answer)
                                                                <div class="answer-item d-flex align-items-start mb-2 pb-2 border-bottom">
                                                                    <div class="flex-shrink-0 me-2">
                                                                        <a href="{{ route('users.show', $answer->user) }}">
                                                                            <x-user-avatar :user="$answer->user" :size="32" />
                                                                        </a>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex align-items-center mb-1">
                                                                            <a href="{{ route('users.show', $answer->user) }}" class="text-decoration-none text-dark fw-bold me-2">{{ $answer->user->name }}</a>
                                                                            <small class="text-muted">{{ $answer->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                        <div class="answer-content text-muted small">
                                                                            {{ Str::limit($answer->content, 150) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            
                                                            @if($post->answers_count > 2)
                                                                <div class="text-center mt-2">
                                                                    <a href="{{ route('posts.show', $post) }}" class="text-decoration-none small">РџРѕРєР°Р·Р°С‚СЊ РІСЃРµ РѕС‚РІРµС‚С‹ ({{ $post->answers_count }})</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <div class="comments-list">
                                                    <h6 class="small fw-bold mb-3">{{ $post->comments_count }} {{ trans_choice('РєРѕРјРјРµРЅС‚Р°СЂРёРµРІ|РєРѕРјРјРµРЅС‚Р°СЂРёР№|РєРѕРјРјРµРЅС‚Р°СЂРёСЏ', $post->comments_count) }}</h6>
                                                    
                                                    @if($post->comments->count() > 0)
                                                        @foreach($post->comments->take(3) as $comment)
                                                            <div class="comment mb-3">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 me-3">
                                                                        <a href="{{ route('users.show', $comment->user) }}">
                                                                            <x-user-avatar :user="$comment->user" :size="32" />
                                                                        </a>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <div class="d-flex align-items-center">
                                                                                <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none fw-bold me-2">{{ $comment->user->name }}</a>
                                                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                                            </div>
                                                                            <div class="dropdown">
                                                                                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#595959" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                                                        <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                                                    </svg>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                                    @if(auth()->check() && auth()->id() === $comment->user_id)
                                                                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">Р РµРґР°РєС‚РёСЂРѕРІР°С‚СЊ</a></li>
                                                                                        <li>
                                                                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Р’С‹ СѓРІРµСЂРµРЅС‹?');">
                                                                                                @csrf
                                                                                                @method('DELETE')
                                                                                                <button type="submit" class="dropdown-item text-danger">РЈРґР°Р»РёС‚СЊ</button>
                                                                                            </form>
                                                                                        </li>
                                                                                    @else
                                                                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportCommentModal{{ $comment->id }}">РџРѕР¶Р°Р»РѕРІР°С‚СЊСЃСЏ</a></li>
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="comment-content mt-1 mb-2">
                                                                            {{ $comment->content }}
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                                                <div class="d-flex align-items-center me-3 like-button" data-comment-id="{{ $comment->id }}">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart{{ $comment->likedBy(auth()->user()) ? '-fill text-danger' : '' }} me-1" viewBox="0 0 16 16">
                                                                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                        </svg>
                                                                                    <span class="likes-count" style="pointer-events: none;" {{ $comment->likedBy(auth()->user()) ? 'class=active' : '' }}>{{ $comment->likes_count > 0 ? $comment->likes_count : '' }}</span>
                                                                                </div>
                                                                                <div class="d-flex align-items-center reply-button" data-comment-id="{{ $comment->id }}">
                                                                                    <a href="#" class="text-decoration-none text-muted small">РћС‚РІРµС‚РёС‚СЊ</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        
                                                        @if($post->comments->count() > 3)
                                                            <div class="text-center mb-3">
                                                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">РЎРјРѕС‚СЂРµС‚СЊ РІСЃРµ РєРѕРјРјРµРЅС‚Р°СЂРёРё ({{ $post->comments_count }})</a>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="text-center py-3">
                                                            <p class="text-muted mb-0">Р‘СѓРґСЊС‚Рµ РїРµСЂРІС‹Рј, РєС‚Рѕ РѕСЃС‚Р°РІРёС‚ РєРѕРјРјРµРЅС‚Р°СЂРёР№!</p>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                @auth
                                                    <div class="comment-form-container mt-3">
                                                        <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="comment-form">
                                                            @csrf
                                                            <div class="input-group">
                                                                <textarea name="content" class="form-control" rows="1" placeholder="РљРѕРјРјРµРЅС‚Р°СЂРёР№..." style="border-top-right-radius: 0; border-bottom-right-radius: 0;"></textarea>
                                                                <button type="submit" class="btn" style="background-color: #1682FD; color: white; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="text-center py-3">
                                                        <p class="mb-0">Р§С‚РѕР±С‹ РѕСЃС‚Р°РІРёС‚СЊ РєРѕРјРјРµРЅС‚Р°СЂРёР№, <a href="{{ route('login') }}">РІРѕР№РґРёС‚Рµ</a> РёР»Рё <a href="{{ route('register') }}">Р·Р°СЂРµРіРёСЃС‚СЂРёСЂСѓР№С‚РµСЃСЊ</a></p>
                                                    </div>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- РџСЂР°РІР°СЏ РєРѕР»РѕРЅРєР° -->
        <x-right-sidebar :popularTags="$popularTags" :topUsers="$topUsers" :isHomePage="true" />
    </div>
</div>

@push('styles')
<!-- РЎС‚РёР»Рё РґР»СЏ РіР»Р°РІРЅРѕР№ СЃС‚СЂР°РЅРёС†С‹ РїРµСЂРµРЅРµСЃРµРЅС‹ РІ РѕР±С‰РёР№ С„Р°Р№Р» CSS app.css -->
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Р¤СѓРЅРєС†РёСЏ РґР»СЏ РѕР±СЂР°Р±РѕС‚РєРё РєР»РёРєР° РЅР° РєРЅРѕРїРєСѓ Р»Р°Р№РєР°
    function handleLikeButtonClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const commentId = this.dataset.commentId;
        const postId = this.dataset.postId;
        const url = commentId ? `/comments/${commentId}/like` : `/posts/${postId}/like`;
        const likesCount = this.querySelector('.likes-count');
        const heartIcon = this.querySelector('svg');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // РћР±РЅРѕРІР»СЏРµРј СЃС‡РµС‚С‡РёРє Р»Р°Р№РєРѕРІ
            if (likesCount) {
                likesCount.textContent = data.likes_count > 0 ? data.likes_count : '';
                
                // Р”РѕР±Р°РІР»СЏРµРј/СѓРґР°Р»СЏРµРј РєР»Р°СЃСЃ active РґР»СЏ СЃС‡РµС‚С‡РёРєР°
                if (data.liked) {
                    likesCount.classList.add('active');
                } else {
                    likesCount.classList.remove('active');
                }
            }
            
            // РћР±РЅРѕРІР»СЏРµРј SVG РёРєРѕРЅРєСѓ СЃРµСЂРґРµС‡РєР°
            if (heartIcon) {
                if (data.liked) {
                    heartIcon.classList.remove('bi-heart');
                    heartIcon.classList.add('bi-heart-fill', 'text-danger');
                } else {
                    heartIcon.classList.remove('bi-heart-fill', 'text-danger');
                    heartIcon.classList.add('bi-heart');
                }
            }
            
            // РћР±РЅРѕРІР»СЏРµРј СЃС‚РёР»Рё СЃР°РјРёС… РєРЅРѕРїРѕРє
            if (data.liked) {
                this.classList.add('active');
            } else {
                this.classList.remove('active');
            }
        });
    }

    // Р›Р°Р№РєРё
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', handleLikeButtonClick);
    });
    
    // РљРѕРјРјРµРЅС‚Р°СЂРёРё - РїРѕРєР°Р·Р°С‚СЊ/СЃРєСЂС‹С‚СЊ РєРѕРјРјРµРЅС‚Р°СЂРёРё
    document.querySelectorAll('.comment-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.dataset.postId;
            const commentsContainer = document.getElementById(`comments-container-${postId}`);
            
            // РџСЂРѕРІРµСЂСЏРµРј, РѕС‚РѕР±СЂР°Р¶Р°СЋС‚СЃСЏ Р»Рё РєРѕРјРјРµРЅС‚Р°СЂРёРё СЃРµР№С‡Р°СЃ
            const isCommentsVisible = commentsContainer.style.display !== 'none';
            
            // Р”РѕР±Р°РІР»СЏРµРј РєР»Р°СЃСЃ active РґР»СЏ РєРЅРѕРїРєРё РєРѕРјРјРµРЅС‚Р°СЂРёСЏ
            if (!isCommentsVisible) {
                this.classList.add('active');
                const img = this.querySelector('img');
                if (img) {
                    img.classList.add('active');
                }
                
                // Р”РѕР±Р°РІР»СЏРµРј РєР»Р°СЃСЃ active РґР»СЏ СЃС‡РµС‚С‡РёРєР° РєРѕРјРјРµРЅС‚Р°СЂРёРµРІ
                const commentsCount = this.querySelector('.comments-count');
                if (commentsCount) {
                    commentsCount.classList.add('active');
                }
                
                // РџРѕРєР°Р·С‹РІР°РµРј РєРѕРјРјРµРЅС‚Р°СЂРёРё
                commentsContainer.style.display = 'block';
                
                // Р•СЃР»Рё РїРѕР»СЊР·РѕРІР°С‚РµР»СЊ РєР»РёРєРЅСѓР» РЅР° РѕР±Р»Р°СЃС‚СЊ РєРѕРјРјРµРЅС‚Р°СЂРёРµРІ, РѕСЃС‚Р°РЅР°РІР»РёРІР°РµРј РІСЃРїР»С‹С‚РёРµ СЃРѕР±С‹С‚РёСЏ
                commentsContainer.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
                
                // РћР±СЂР°Р±Р°С‚С‹РІР°РµРј РѕС‚РїСЂР°РІРєСѓ РєРѕРјРјРµРЅС‚Р°СЂРёСЏ Р±РµР· РїРµСЂРµР·Р°РіСЂСѓР·РєРё СЃС‚СЂР°РЅРёС†С‹
                const commentForm = commentsContainer.querySelector('.comment-form');
                if (commentForm) {
                    commentForm.addEventListener('submit', function(event) {
                        event.preventDefault();
                        
                        const formData = new FormData(this);
                        fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // РЈРІРµР»РёС‡РёРІР°РµРј СЃС‡РµС‚С‡РёРє РєРѕРјРјРµРЅС‚Р°СЂРёРµРІ
                                const commentsCount = button.querySelector('.comments-count');
                                if (commentsCount) {
                                    commentsCount.textContent = parseInt(commentsCount.textContent) + 1;
                                }
                                
                                // РћС‡РёС‰Р°РµРј С„РѕСЂРјСѓ
                                commentForm.reset();
                                
                                // Р”РѕР±Р°РІР»СЏРµРј РЅРѕРІС‹Р№ РєРѕРјРјРµРЅС‚Р°СЂРёР№ РІ СЃРїРёСЃРѕРє
                                const commentsList = commentsContainer.querySelector('.comments-list');
                                if (commentsList) {
                                    const newComment = document.createElement('div');
                                    newComment.className = 'comment mb-3';
                                    newComment.innerHTML = `
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <a href="${data.user_url}">
                                                    <img src="${data.user_avatar}" alt="${data.user_name}" class="rounded-circle" width="32" height="32">
                                                </a>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <a href="${data.user_url}" class="text-decoration-none fw-bold me-2">${data.user_name}</a>
                                                        <small class="text-muted">С‚РѕР»СЊРєРѕ С‡С‚Рѕ</small>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#595959" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#" data-comment-id="${data.comment_id}">РџРѕР¶Р°Р»РѕРІР°С‚СЊСЃСЏ</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="comment-content mt-1 mb-2">
                                                    ${data.content}
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex align-items-center me-3 like-button" data-comment-id="${data.comment_id}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart me-1" viewBox="0 0 16 16">
                                                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                        </svg>
                                                        <span class="likes-count" style="pointer-events: none;"></span>
                                                    </div>
                                                    <div class="d-flex align-items-center reply-button" data-comment-id="${data.comment_id}">
                                                        <a href="#" class="text-decoration-none text-muted small">РћС‚РІРµС‚РёС‚СЊ</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    
                                    // Р•СЃР»Рё СЃРїРёСЃРѕРє РµС‰Рµ РЅРµ СЃСѓС‰РµСЃС‚РІСѓРµС‚, СЃРѕР·РґР°РµРј РµРіРѕ
                                    if (!commentsList) {
                                        const newCommentsList = document.createElement('div');
                                        newCommentsList.className = 'comments-list';
                                        newCommentsList.appendChild(newComment);
                                        commentsContainer.insertBefore(newCommentsList, commentForm.parentNode);
                                    } else {
                                        commentsList.appendChild(newComment);
                                    }
                                }
                                
                                // Р”РѕР±Р°РІР»СЏРµРј РѕР±СЂР°Р±РѕС‚С‡РёРє РєР»РёРєР° РґР»СЏ РЅРѕРІРѕРіРѕ РєРѕРјРјРµРЅС‚Р°СЂРёСЏ
                                const newLikeButton = newComment.querySelector('.like-button');
                                if (newLikeButton) {
                                    newLikeButton.addEventListener('click', handleLikeButtonClick);
                                }
                            }
                        });
                    });
                }
            } else {
                // РЎРєСЂС‹РІР°РµРј РєРѕРјРјРµРЅС‚Р°СЂРёРё Рё СѓР±РёСЂР°РµРј Р°РєС‚РёРІРЅС‹Рµ РєР»Р°СЃСЃС‹
                this.classList.remove('active');
                const img = this.querySelector('img');
                if (img) {
                    img.classList.remove('active');
                }
                
                const commentsCount = this.querySelector('.comments-count');
                if (commentsCount) {
                    commentsCount.classList.remove('active');
                }
                
                commentsContainer.style.display = 'none';
            }
        });
    });
    
    // РџСЂРµРґРѕС‚РІСЂР°С‰Р°РµРј РїРµСЂРµС…РѕРґ РїСЂРё РєР»РёРєРµ РЅР° РїРѕСЃС‚, РµСЃР»Рё РєР»РёРє Р±С‹Р» РїРѕ РєРѕРјРјРµРЅС‚Р°СЂРёСЏРј
    document.querySelectorAll('.post-content').forEach(content => {
        content.addEventListener('click', function(e) {
            if (e.target.closest('.comments-container')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    
    // Р РµРїРѕСЃС‚С‹ - РєРѕРїРёСЂРѕРІР°РЅРёРµ СЃСЃС‹Р»РєРё
    document.querySelectorAll('.repost-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.closest('.post-card').querySelector('.like-button').dataset.postId;
            const postUrl = `/posts/${postId}`;
            
            navigator.clipboard.writeText(window.location.origin + postUrl).then(() => {
                // Р”РѕР±Р°РІР»СЏРµРј РєР»Р°СЃСЃ active РґР»СЏ РІРёР·СѓР°Р»СЊРЅРѕРіРѕ СЌС„С„РµРєС‚Р°
                this.classList.add('active');
                const img = this.querySelector('img');
                if (img) {
                    img.classList.add('active');
                }
                const repostsCount = this.querySelector('.reposts-count');
                if (repostsCount) {
                    repostsCount.classList.add('active');
                }
                
                // РЎРѕР·РґР°РµРј Рё РїРѕРєР°Р·С‹РІР°РµРј СЃРѕРѕР±С‰РµРЅРёРµ
                const toast = document.createElement('div');
                toast.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                toast.style.zIndex = '9999';
                toast.textContent = 'РЎСЃС‹Р»РєР° СЃРєРѕРїРёСЂРѕРІР°РЅР° РІ Р±СѓС„РµСЂ РѕР±РјРµРЅР°';
                
                document.body.appendChild(toast);
                
                // РЈРґР°Р»СЏРµРј СЃРѕРѕР±С‰РµРЅРёРµ Рё РєР»Р°СЃСЃ active С‡РµСЂРµР· 2 СЃРµРєСѓРЅРґС‹
                setTimeout(() => {
                    toast.remove();
                    this.classList.remove('active');
                    if (img) {
                        img.classList.remove('active');
                    }
                    if (repostsCount) {
                        repostsCount.classList.remove('active');
                    }
                }, 2000);
                
                // РЈРІРµР»РёС‡РёРІР°РµРј СЃС‡РµС‚С‡РёРє СЂРµРїРѕСЃС‚РѕРІ
                if (repostsCount) {
                    repostsCount.textContent = parseInt(repostsCount.textContent) + 1;
                }
            }).catch(err => {
                console.error('РќРµ СѓРґР°Р»РѕСЃСЊ СЃРєРѕРїРёСЂРѕРІР°С‚СЊ: ', err);
            });
        });
    });
    
    // Р—Р°РєР»Р°РґРєРё
    document.querySelectorAll('.bookmark-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.dataset.postId;
            const bookmarkImg = this.querySelector('img');
            
            fetch(`/posts/${postId}/bookmark`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // РћР±РЅРѕРІР»СЏРµРј СЃРѕСЃС‚РѕСЏРЅРёРµ РЅР° РІСЃРµС… РєР°СЂС‚РѕС‡РєР°С… СЃ СЌС‚РёРј РїРѕСЃС‚РѕРј
                document.querySelectorAll(`.bookmark-button[data-post-id="${postId}"]`).forEach(btn => {
                    if (data.bookmarked) {
                        btn.classList.add('active');
                        const img = btn.querySelector('img');
                        if (img) {
                            img.classList.add('bookmarked');
                        }
                    } else {
                        btn.classList.remove('active');
                        const img = btn.querySelector('img');
                        if (img) {
                            img.classList.remove('bookmarked');
                        }
                    }
                });
            });
        });
    });

    // РћР±СЂР°Р±РѕС‚РєР° РѕС‚РІРµС‚РѕРІ РЅР° РєРѕРјРјРµРЅС‚Р°СЂРёРё
    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const commentId = this.dataset.commentId;
            const commentElement = this.closest('.comment');
            
            // РџСЂРѕРІРµСЂСЏРµРј, РµСЃС‚СЊ Р»Рё СѓР¶Рµ С„РѕСЂРјР° РґР»СЏ РѕС‚РІРµС‚Р°
            let replyForm = commentElement.querySelector('.reply-form-container');
            
            // Р•СЃР»Рё С„РѕСЂРјР° СѓР¶Рµ РµСЃС‚СЊ, РїСЂРѕСЃС‚Рѕ РїРµСЂРµРєР»СЋС‡Р°РµРј РµС‘ РІРёРґРёРјРѕСЃС‚СЊ
            if (replyForm) {
                if (replyForm.style.display === 'none') {
                    replyForm.style.display = 'block';
                    replyForm.querySelector('textarea').focus();
                } else {
                    replyForm.style.display = 'none';
                }
                return;
            }
            
            // Р•СЃР»Рё С„РѕСЂРјС‹ РЅРµС‚, СЃРѕР·РґР°РµРј РµС‘
            replyForm = document.createElement('div');
            replyForm.className = 'reply-form-container mt-3';
            replyForm.innerHTML = `
                <form class="reply-form" action="/comments/${commentId}/replies" method="POST">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                    <div class="input-group">
                        <textarea name="content" class="form-control" rows="1" placeholder="РћС‚РІРµС‚РёС‚СЊ..." style="border-top-right-radius: 0; border-bottom-right-radius: 0;"></textarea>
                        <button type="submit" class="btn" style="background-color: #1682FD; color: white; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            `;
            
            // Р”РѕР±Р°РІР»СЏРµРј С„РѕСЂРјСѓ РїРѕСЃР»Рµ СЃРѕРґРµСЂР¶РёРјРѕРіРѕ РєРѕРјРјРµРЅС‚Р°СЂРёСЏ
            commentElement.querySelector('.flex-grow-1').appendChild(replyForm);
            
            // Р¤РѕРєСѓСЃРёСЂСѓРµРјСЃСЏ РЅР° С‚РµРєСЃС‚РѕРІРѕРј РїРѕР»Рµ
            replyForm.querySelector('textarea').focus();
            
            // РћР±СЂР°Р±Р°С‚С‹РІР°РµРј РѕС‚РїСЂР°РІРєСѓ РѕС‚РІРµС‚Р°
            replyForm.querySelector('form').addEventListener('submit', function(event) {
                event.preventDefault();
                
                const formData = new FormData(this);
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // РЎРєСЂС‹РІР°РµРј С„РѕСЂРјСѓ
                        replyForm.style.display = 'none';
                        
                        // РћС‡РёС‰Р°РµРј С„РѕСЂРјСѓ
                        replyForm.querySelector('form').reset();
                        
                        // РЎРѕР·РґР°РµРј РІСЃРїР»С‹РІР°СЋС‰РµРµ СЃРѕРѕР±С‰РµРЅРёРµ
                        const toast = document.createElement('div');
                        toast.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                        toast.style.zIndex = '9999';
                        toast.textContent = 'РћС‚РІРµС‚ РѕС‚РїСЂР°РІР»РµРЅ';
                        
                        document.body.appendChild(toast);
                        
                        // РЈРґР°Р»СЏРµРј СЃРѕРѕР±С‰РµРЅРёРµ С‡РµСЂРµР· 2 СЃРµРєСѓРЅРґС‹
                        setTimeout(() => {
                            toast.remove();
                        }, 2000);
                    }
                });
            });
        });
    });
});
</script>
@endpush

<!-- РњРѕРґР°Р»СЊРЅС‹Рµ РѕРєРЅР° РґР»СЏ Р¶Р°Р»РѕР± РЅР° РїРѕСЃС‚С‹ -->
@foreach($posts as $post)
<div class="modal fade" id="reportPostModal{{ $post->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">РџРѕР¶Р°Р»РѕРІР°С‚СЊСЃСЏ РЅР° РїРѕСЃС‚</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('posts.report', $post) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">РўРёРї Р¶Р°Р»РѕР±С‹</label>
                        <select name="type" class="form-select" required>
                            <option value="">Р’С‹Р±РµСЂРёС‚Рµ С‚РёРї Р¶Р°Р»РѕР±С‹</option>
                            <option value="СЃРїР°Рј">РЎРїР°Рј</option>
                            <option value="РѕСЃРєРѕСЂР±Р»РµРЅРёРµ">РћСЃРєРѕСЂР±Р»РµРЅРёРµ</option>
                            <option value="РЅРµРїСЂРёРµРјР»РµРјС‹Р№ РєРѕРЅС‚РµРЅС‚">РќРµРїСЂРёРµРјР»РµРјС‹Р№ РєРѕРЅС‚РµРЅС‚</option>
                            <option value="РЅР°СЂСѓС€РµРЅРёРµ Р°РІС‚РѕСЂСЃРєРёС… РїСЂР°РІ">РќР°СЂСѓС€РµРЅРёРµ Р°РІС‚РѕСЂСЃРєРёС… РїСЂР°РІ</option>
                            <option value="РґСЂСѓРіРѕРµ">Р”СЂСѓРіРѕРµ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">РџСЂРёС‡РёРЅР° Р¶Р°Р»РѕР±С‹</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="РћРїРёС€РёС‚Рµ РїРѕРґСЂРѕР±РЅРµРµ РїСЂРёС‡РёРЅСѓ Р¶Р°Р»РѕР±С‹..." minlength="10" maxlength="1000"></textarea>
                        <div class="form-text">РњРёРЅРёРјСѓРј 10 СЃРёРјРІРѕР»РѕРІ</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">РћС‚РјРµРЅР°</button>
                    <button type="submit" class="btn btn-danger">РћС‚РїСЂР°РІРёС‚СЊ Р¶Р°Р»РѕР±Сѓ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- РњРѕРґР°Р»СЊРЅС‹Рµ РѕРєРЅР° РґР»СЏ РєРѕРјРјРµРЅС‚Р°СЂРёРµРІ -->
@foreach($post->comments as $comment)
    <!-- РњРѕРґР°Р»СЊРЅРѕРµ РѕРєРЅРѕ РґР»СЏ СЂРµРґР°РєС‚РёСЂРѕРІР°РЅРёСЏ РєРѕРјРјРµРЅС‚Р°СЂРёСЏ -->
    <div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Р РµРґР°РєС‚РёСЂРѕРІР°РЅРёРµ РєРѕРјРјРµРЅС‚Р°СЂРёСЏ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('comments.update', $comment) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <textarea name="content" class="form-control" rows="3" required>{{ $comment->content }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">РћС‚РјРµРЅР°</button>
                        <button type="submit" class="btn" style="background-color: #1682FD; color: white;">РЎРѕС…СЂР°РЅРёС‚СЊ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- РњРѕРґР°Р»СЊРЅРѕРµ РѕРєРЅРѕ РґР»СЏ Р¶Р°Р»РѕР±С‹ РЅР° РєРѕРјРјРµРЅС‚Р°СЂРёР№ -->
    <div class="modal fade" id="reportCommentModal{{ $comment->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">РџРѕР¶Р°Р»РѕРІР°С‚СЊСЃСЏ РЅР° РєРѕРјРјРµРЅС‚Р°СЂРёР№</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('comments.report', $comment) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">РўРёРї Р¶Р°Р»РѕР±С‹</label>
                            <select name="type" class="form-select" required>
                                <option value="">Р’С‹Р±РµСЂРёС‚Рµ С‚РёРї Р¶Р°Р»РѕР±С‹</option>
                                <option value="СЃРїР°Рј">РЎРїР°Рј</option>
                                <option value="РѕСЃРєРѕСЂР±Р»РµРЅРёРµ">РћСЃРєРѕСЂР±Р»РµРЅРёРµ</option>
                                <option value="РЅРµРїСЂРёРµРјР»РµРјС‹Р№ РєРѕРЅС‚РµРЅС‚">РќРµРїСЂРёРµРјР»РµРјС‹Р№ РєРѕРЅС‚РµРЅС‚</option>
                                <option value="РЅР°СЂСѓС€РµРЅРёРµ Р°РІС‚РѕСЂСЃРєРёС… РїСЂР°РІ">РќР°СЂСѓС€РµРЅРёРµ Р°РІС‚РѕСЂСЃРєРёС… РїСЂР°РІ</option>
                                <option value="РґСЂСѓРіРѕРµ">Р”СЂСѓРіРѕРµ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">РџСЂРёС‡РёРЅР° Р¶Р°Р»РѕР±С‹</label>
                            <textarea name="reason" class="form-control" rows="3" required placeholder="РћРїРёС€РёС‚Рµ РїРѕРґСЂРѕР±РЅРµРµ РїСЂРёС‡РёРЅСѓ Р¶Р°Р»РѕР±С‹..." minlength="10" maxlength="1000"></textarea>
                            <div class="form-text">РњРёРЅРёРјСѓРј 10 СЃРёРјРІРѕР»РѕРІ</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">РћС‚РјРµРЅР°</button>
                        <button type="submit" class="btn btn-danger">РћС‚РїСЂР°РІРёС‚СЊ Р¶Р°Р»РѕР±Сѓ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection 
