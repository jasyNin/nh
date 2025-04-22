<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Основные стили -->
    <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/comments.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    
    <!-- Дополнительные стили из секций -->
    @stack('styles')
</head>
<body>
    <!-- Навигация -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="НейроХаб" height="32" class="me-2">
                <span class="text">НЕЙРОХАБ</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link nav-auth-btn register" href="{{ route('register') }}">
                                Регистрация
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-auth-btn login" href="{{ route('login') }}">
                                Войти
                            </a>
                        </li>
                    @else
                        <div class="search-container me-3">
                            <button type="button" class="search-trigger">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="#272727" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <div class="search-form">
                                <div class="search-input-wrapper">
                                    <input type="text" class="search-input" placeholder="Поиск">
                                </div>
                            </div>
                        </div>
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="{{ asset('images/bell.svg') }}" alt="Уведомления" width="24" height="24">
                                <span class="notification-badge">0</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end notifications-dropdown">
                                <div class="notifications-list"></div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Все уведомления</a>
                            </div>
                        </li>
                        <li class="nav-item me-3">
                            <a href="{{ route('posts.create') }}" class="create-post-btn">
                                <img src="{{ asset('images/pen.svg') }}" alt="Написать" width="24" height="24" class="me-2">
                                Написать
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link p-0 d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <x-user-avatar :user="Auth::user()" :size="40" />
                                <svg class="ms-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6L8 10L12 6" stroke="#272727" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end user-dropdown" style="width: 240px; border-radius: 12px; border: none; box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.12);">
                                <div class="px-3 py-2" style="color: #272727; font-size: 17px; font-weight: 500;">
                                    Мой профиль
                                </div>
                                <a href="{{ route('users.show', Auth::user()) }}" class="text-decoration-none dropdown-item">
                                    <div class="px-3 py-3 d-flex align-items-center" style="background: transparent;">
                                        <x-user-avatar :user="Auth::user()" :size="40" />
                                        <div style="margin-left: 12px;">
                                            <div style="color: #272727; font-weight: 500;">{{ Auth::user()->name }}</div>
                                            <div style="color: #808080; font-size: 13px;">Эксперт</div>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route('profile.edit') }}" style="color: #272727;">
                                    <img src="{{ asset('images/settings.svg') }}" alt="Настройки" width="20" height="20" class="me-2">
                                    Настройки
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 d-flex align-items-center" style="color: #272727;">
                                        <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.75 6.875L16.875 10L13.75 13.125M8.75 10H16.875M8.75 16.875H3.75C3.58424 16.875 3.42527 16.8092 3.30806 16.6919C3.19085 16.5747 3.125 16.4158 3.125 16.25V3.75C3.125 3.58424 3.19085 3.42527 3.30806 3.30806C3.42527 3.19085 3.58424 3.125 3.75 3.125H8.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Выход
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <main>
        @if(session('success') || session('error'))
            <div class="container" style="margin-top: 80px;">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Футер -->
    

    <!-- Скрипты -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Основной скрипт приложения -->
    <script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/comments.js') }}"></script>
    
    <!-- Скрипт поиска только для авторизованных пользователей -->
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchTrigger = document.querySelector('.search-trigger');
            if (searchTrigger) {
                searchTrigger.addEventListener('click', function() {
                    const container = this.closest('.search-container');
                    container.classList.toggle('active');
                    if (container.classList.contains('active')) {
                        container.querySelector('.search-input').focus();
                    }
                });

                // Закрытие поиска при клике вне формы
                document.addEventListener('click', function(event) {
                    const container = document.querySelector('.search-container');
                    if (container && !container.contains(event.target)) {
                        container.classList.remove('active');
                    }
                });
            }
        });
    </script>
    @endauth
    
    @stack('scripts')

    <style>
    .user-dropdown .dropdown-item {
        padding: 0;
        margin: 0;
    }
    .user-dropdown .dropdown-item:hover {
        background-color: #F5F5F5;
    }
    .user-dropdown .dropdown-item:active {
        background-color: #F5F5F5;
        color: #272727;
    }
    </style>
</body>
</html> 