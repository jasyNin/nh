@extends('layouts.app')

@section('title', 'Ответы')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-10">
            <h5 class="mb-3">Ответы</h5>
            @if($answers->isEmpty() && $commentsToUser->isEmpty() && $repliesToUser->isEmpty())
                <div class="card border-0">
                    <div class="card-body">
                        <div class="text-center py-5">
                            <img src="{{ asset('images/ansvers.svg') }}" class="mb-3" width="48" height="48" alt="Ответы" style="filter: brightness(0);">
                            <h5>Пока нет ответов и комментариев</h5>
                            <p class="text-muted">Ответьте на вопрос или оставьте комментарий, чтобы он появился здесь</p>
                        </div>
                    </div>
                </div>
            @else
                @php
                    $allItems = collect();
                    
                    // Добавляем комментарии к постам пользователя
                    foreach($commentsToUser as $comment) {
                        $allItems->push([
                            'type' => 'comment',
                            'item' => $comment,
                            'created_at' => $comment->created_at
                        ]);
                    }
                    
                    // Добавляем ответы на комментарии пользователя
                    foreach($repliesToUser as $reply) {
                        $allItems->push([
                            'type' => 'reply',
                            'item' => $reply,
                            'created_at' => $reply->created_at
                        ]);
                    }
                    
                    // Сортируем по дате создания (новые сверху)
                    $allItems = $allItems->sortByDesc('created_at');
                @endphp

                @foreach($allItems as $item)
                    <div class="card mb-2 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-2">
                                    <div class="position-relative">
                                        <x-user-avatar :user="$item['item']->user" :size="42" />
                                        <x-rank-icon :user="$item['item']->user" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('users.show', $item['item']->user) }}" class="user-name me-2">{{ $item['item']->user->name }}</a>
                                        <span class="timestamp">{{ $item['item']->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-muted small mb-2">{{ $item['item']->user->rank_name }}</div>
                                    <div class="comment-content">{{ $item['item']->content }}</div>
                                    <div class="mt-2">
                                        @if($item['type'] === 'comment')
                                            <span class="text-muted">оставил комментарий к посту:</span>
                                            <a href="{{ route('posts.show', $item['item']->post) }}" class="post-link">
                                                {{ $item['item']->post->title }}
                                            </a>
                                        @elseif($item['type'] === 'reply')
                                            <span class="text-muted">ответил на ваш комментарий в посте:</span>
                                            <a href="{{ route('posts.show', $item['item']->comment->post) }}" class="post-link">
                                                {{ $item['item']->comment->post->title }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $answers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .comment-textarea {
        border-radius: 8px 0 0 8px;
        border: 1px solid #e0e0e0;
        padding: 10px 15px;
        resize: none;
    }
    .comment-submit-btn {
        border-radius: 0 8px 8px 0;
        padding: 10px 20px;
    }
    .comment-content {
        margin-top: 5px;
        color: #333;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .comments-section {
        border-top: 1px solid #eee;
        padding-top: 15px;
    }
    .comment {
        padding: 12px;
        border-radius: 8px;
        background-color: #f8f9fa;
        margin-bottom: 12px;
    }
    .reply-textarea {
        border-radius: 8px 0 0 8px;
        border: 1px solid #e0e0e0;
        padding: 8px 12px;
        resize: none;
        font-size: 0.9rem;
    }
    .reply-submit-btn {
        border-radius: 0 8px 8px 0;
        padding: 8px 15px;
        font-size: 0.9rem;
    }
    .reply {
        padding: 10px;
        border-radius: 8px;
        background-color: #f8f9fa;
        margin-bottom: 10px;
    }
    .reply-content {
        margin-top: 3px;
        color: #333;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .post-link {
        color: #1976d2;
        text-decoration: none;
        font-weight: 500;
    }
    .post-link:hover {
        text-decoration: underline;
    }
    .user-name {
        color: #333;
        font-weight: 600;
        text-decoration: none;
    }
    .user-name:hover {
        color: #1976d2;
    }
    .timestamp {
        color: #666;
        font-size: 0.85rem;
    }
    .shadow-sm {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
    }
</style>
@endsection 