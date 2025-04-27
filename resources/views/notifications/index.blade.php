@extends('layouts.app')

@section('title', 'Уведомления')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Уведомления</span>
                    @if($notifications->isNotEmpty())
                        <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                Отметить все как прочитанные
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    @if($notifications->isEmpty())
                        <div class="text-center py-5">
                            <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none">
                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 16V12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h5>У вас пока нет уведомлений</h5>
                            <p class="text-muted">Здесь будут появляться уведомления о новых ответах и комментариях</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ $notification->read_at ? '' : 'list-group-item-primary' }}">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <x-user-avatar :user="$notification->fromUser" :size="40" />
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <a href="{{ route('users.show', $notification->fromUser->id) }}" class="text-decoration-none text-dark fw-bold">{{ $notification->fromUser->name }}</a>
                                                    <span class="text-muted ms-2">
                                                        @if($notification->type === 'like')
                                                            поставил(а) лайк
                                                        @elseif($notification->type === 'comment')
                                                            оставил(а) комментарий
                                                        @elseif($notification->type === 'rating')
                                                            оценил(а)
                                                        @endif
                                                    </span>
                                                </div>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="text-muted">
                                                <small>К посту: <a href="{{ route('posts.show', $notification->notifiable_id) }}" class="text-decoration-none">{{ $notification->notifiable->title }}</a></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $notifications->links() }}
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