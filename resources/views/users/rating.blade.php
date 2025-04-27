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

                            @foreach($sortedUsers as $index => $user)
                                <a href="{{ route('users.show', $user) }}" class="list-group-item list-group-item-action border-bottom user-rating-item">
                                    <div class="d-flex align-items-center">
                                        <div class="position-number me-4">
                                            <div style="width: 24px; height: 24px; position: relative; background: #1682FD; border-radius: 6px; display: flex; align-items: center; justify-content: center">
                                                <span style="color: white; font-size: 13px; font-weight: 400; line-height: 12px;">{{ $loop->iteration }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-grow-1">
                                            <div class="position-relative">
                                                <x-user-avatar :user="$user" :size="40" class="me-3" />
                                                <x-rank-icon :user="$user" />
                                            </div>
                                            <div style="margin-left: 12px;">
                                                <div class="fw-bold">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->rank_name }}</small>
                                            </div>
                                            <div class="ms-auto">
                                                <span>{{ $user->rating }} баллов</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
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
            @if(count($users) > 0)
                <div class="card mb-4 border-0" style="border-radius: 8px;">
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
.user-rating-item {
    text-decoration: none;
    color: inherit;
}
.user-rating-item:hover {
    background-color: #F8F9FA;
    color: inherit;
    text-decoration: none;
}
.user-rating-item:active {
    transform: translateY(0);
    box-shadow: none;
}
</style>
@endsection 