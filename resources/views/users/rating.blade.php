@extends('layouts.app')

@section('title', 'Рейтинг пользователей')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <h5 class="mb-4">Рейтинг пользователей</h5>
            
            <div class="card border-0" style="border-radius: 8px;">
                <div class="card-body p-0">
                    @if($users->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/rank.svg') }}" class="mb-3" width="48" height="48" alt="Рейтинг">
                            <h5>Пока нет пользователей</h5>
                            <p class="text-muted">Зарегистрируйтесь, чтобы начать общение</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($users as $index => $user)
                                <div class="list-group-item border-bottom">
                                    <div class="d-flex align-items-center">
                                        <div class="position-number me-4">
                                            <div style="width: 24px; height: 24px; position: relative; background: #1682FD; border-radius: 6px; display: flex; align-items: center; justify-content: center">
                                                <span style="color: white; font-size: 13px; font-weight: 400; line-height: 12px;">{{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <x-user-avatar :user="$user" :size="40" class="me-3" />
                                            <div style="margin-left: 12px;">
                                                <div class="fw-bold">{{ $user->name }}</div>
                                                <small class="text-muted">новичок</small>
                                            </div>
                                            <div class="ms-auto">
                                                <span>{{ $user->rating }} баллов</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 px-3">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Правая колонка -->
        <div class="col-md-3 right-sidebar" style="margin-top: 20px;">
            @if(count($topUsers) > 0)
                <div class="card mb-4 border-0" style="border-radius: 8px;">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="card-title">Топ пользователей</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($topUsers->take(3) as $user)
                            <a href="{{ route('users.show', $user) }}" class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex align-items-center">
                                    <x-user-avatar :user="$user" :size="40" class="me-3" style="margin-right: 12px !important;" />
                                    <div style="margin-left: 12px;">
                                        <div class="user-name fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->rating ?? $user->posts_count }} {{ isset($user->rating) ? __('rating.points') : __('posts.posts.' . min($user->posts_count, 20)) }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.list-group-item {
    padding: 16px 24px;
}
.list-group-item:last-child {
    border-bottom: none !important;
}
.border-bottom {
    border-bottom: 1px solid #E7E7E7 !important;
}
.card {
    border-radius: 8px !important;
    overflow: hidden;
}
</style>
@endsection 