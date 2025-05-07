@extends('layouts.app')

@section('title', 'Админ панель')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <div class="admin-dashboard">
                <h1 class="mb-4">Панель управления</h1>
                
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title">Пользователи</h5>
                                <p class="card-text">Управление пользователями, ролями и правами доступа</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Перейти</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title">Посты</h5>
                                <p class="card-text">Модерация постов, управление тегами и категориями</p>
                                <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">Перейти</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title">Теги</h5>
                                <p class="card-text">Управление тегами, категориями и метаданными</p>
                                <a href="{{ route('admin.tags.index') }}" class="btn btn-primary">Перейти</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title">Жалобы</h5>
                                <p class="card-text">Просмотр и обработка жалоб от пользователей</p>
                                <a href="{{ route('admin.complaints.index') }}" class="btn btn-primary">Перейти</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title">Настройки</h5>
                                <p class="card-text">Настройка параметров сайта и системных опций</p>
                                <a href="{{ route('admin.settings.index') }}" class="btn btn-primary">Перейти</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title">Отладка бота</h5>
                                <p class="card-text">Тестирование и отладка ответов бота на вопросы</p>
                                <a href="{{ route('admin.bot-debug.index') }}" class="btn btn-primary">Перейти</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 