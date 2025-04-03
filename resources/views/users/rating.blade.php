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
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Рейтинг пользователей</h5>
                </div>
                <div class="card-body">
                    @if($users->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/rank.svg') }}" class="mb-3" width="48" height="48" alt="Рейтинг">
                            <h5>Пока нет пользователей</h5>
                            <p class="text-muted">Зарегистрируйтесь, чтобы начать общение</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($users as $user)
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <x-user-avatar :user="$user" :size="40" />
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <a href="{{ route('users.show', $user) }}" class="text-decoration-none text-dark fw-bold">{{ $user->name }}</a>
                                                    <small class="text-muted d-block">{{ $user->posts_count }} {{ __('posts.posts.' . min($user->posts_count, 20)) }}</small>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-primary me-2">{{ $user->rating }}</span>
                                                    <span class="text-muted">{{ $user->answers_count }} {{ __('answers.answers.' . min($user->answers_count, 20)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Правая колонка -->
        <x-right-sidebar :topUsers="$topUsers->take(3)" />
    </div>
</div>
@endsection 