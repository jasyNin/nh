@extends('layouts.app')

@section('title', 'Поиск')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Поиск</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('search.index') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Поиск по постам, ответам и комментариям...">
                            <button class="btn btn-primary" type="submit">Поиск</button>
                        </div>
                    </form>

                    @if(request()->has('q'))
                        @if($posts->isEmpty() && $answers->isEmpty() && $comments->isEmpty())
                            <div class="text-center py-5">
                                <img src="{{ asset('images/search.svg') }}" class="mb-3" width="48" height="48" alt="Поиск">
                                <h5>Ничего не найдено</h5>
                                <p class="text-muted">Попробуйте изменить поисковый запрос</p>
                            </div>
                        @else
                            @if(!$posts->isEmpty())
                                <h6 class="mb-3">Посты</h6>
                                <div class="list-group mb-4">
                                    @foreach($posts as $post)
                                        <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-1">{{ $post->title }}</h6>
                                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">{{ Str::limit($post->content, 100) }}</p>
                                            <small class="text-muted">
                                                {{ $post->user->name }} • 
                                                {{ $post->answers_count }} {{ __('answers.answers.' . min($post->answers_count, 20)) }} • 
                                                {{ $post->comments_count }} {{ __('comments.comments.' . min($post->comments_count, 20)) }}
                                            </small>
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            @if(!$answers->isEmpty())
                                <h6 class="mb-3">Ответы</h6>
                                <div class="list-group mb-4">
                                    @foreach($answers as $answer)
                                        <a href="{{ route('posts.show', $answer->post) }}#answer-{{ $answer->id }}" class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-1">Ответ на пост "{{ $answer->post->title }}"</h6>
                                                <small class="text-muted">{{ $answer->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">{{ Str::limit($answer->content, 100) }}</p>
                                            <small class="text-muted">
                                                {{ $answer->user->name }} • 
                                                {{ $answer->comments_count }} {{ __('comments.comments.' . min($answer->comments_count, 20)) }}
                                            </small>
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            @if(!$comments->isEmpty())
                                <h6 class="mb-3">Комментарии</h6>
                                <div class="list-group">
                                    @foreach($comments as $comment)
                                        <a href="{{ route('posts.show', $comment->commentable->post) }}#comment-{{ $comment->id }}" class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-1">Комментарий к {{ strpos($comment->commentable_type, 'Post') !== false ? 'посту' : 'ответу' }} "{{ $comment->commentable->post->title }}"</h6>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-1">{{ Str::limit($comment->content, 100) }}</p>
                                            <small class="text-muted">{{ $comment->user->name }}</small>
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-4">
                                {{ $posts->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('images/search.svg') }}" class="mb-3" width="48" height="48" alt="Поиск">
                            <h5>Введите поисковый запрос</h5>
                            <p class="text-muted">Начните поиск, введя текст в поле выше</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Правая колонка -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">Популярные теги</div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('tags.show', $tag) }}" class="badge bg-secondary text-decoration-none">
                                #{{ $tag->name }}
                                <span class="ms-1">{{ $tag->posts_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Топ пользователей</div>
                <div class="list-group list-group-flush">
                    @foreach($topUsers as $user)
                        <a href="{{ route('users.show', $user) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <x-user-avatar :user="$user" :size="32" class="me-3" />
                            <div>
                                <h6 class="mb-0">{{ $user->name }}</h6>
                                <small class="text-muted">{{ $user->posts_count }} {{ __('posts.posts.' . min($user->posts_count, 20)) }}</small>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 