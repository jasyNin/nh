<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Основные стили -->
    <link href="/css/app.css?v={{ time() }}" rel="stylesheet">
    
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
                            <div class="dropdown-menu dropdown-menu-end user-dropdown" style="width: 240px;">
                                <div class="px-3 py-2 text-muted">
                                    Мой профиль
                                </div>
                                <div class="px-3 py-3 d-flex align-items-center border-bottom">
                                    <x-user-avatar :user="Auth::user()" :size="48" class="me-2" />
                                    <div>
                                        <div class="fw-medium">{{ Auth::user()->name }}</div>
                                        <div class="text-muted small">Пользователь</div>
                                    </div>
                                </div>
                                <a class="dropdown-item py-2 d-flex align-items-center" href="{{ route('profile.show') }}">
                                    <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.5 16.875H3.75C3.58424 16.875 3.42527 16.8092 3.30806 16.6919C3.19085 16.5747 3.125 16.4158 3.125 16.25V15.7031C3.125 14.7391 3.50089 13.8145 4.17085 13.1445C4.84082 12.4746 5.76536 12.0987 6.72937 12.0987H7.27063C7.61211 12.0987 7.95033 12.1481 8.27563 12.2452M13.125 11.875C15.2341 11.875 16.875 10.2341 16.875 8.125C16.875 6.01586 15.2341 4.375 13.125 4.375C11.0159 4.375 9.375 6.01586 9.375 8.125C9.375 10.2341 11.0159 11.875 13.125 11.875ZM11.875 14.375H14.375C15.3391 14.375 16.2637 14.7509 16.9336 15.4209C17.6036 16.0908 17.9795 17.0154 17.9795 17.9794V18.5206C17.9795 18.6864 17.9136 18.8454 17.7964 18.9626C17.6792 19.0798 17.5203 19.1456 17.3544 19.1456H8.89563C8.72986 19.1456 8.57089 19.0798 8.45368 18.9626C8.33647 18.8454 8.27063 18.6864 8.27063 18.5206V17.9794C8.27063 17.0154 8.64652 16.0908 9.31648 15.4209C9.98645 14.7509 10.9109 14.375 11.875 14.375Z" stroke="#272727" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Настройки
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 d-flex align-items-center text-danger">
                                        <svg class="me-2" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.75 6.875L16.875 10L13.75 13.125M8.75 10H16.875M8.75 16.875H3.75C3.58424 16.875 3.42527 16.8092 3.30806 16.6919C3.19085 16.5747 3.125 16.4158 3.125 16.25V3.75C3.125 3.58424 3.19085 3.42527 3.30806 3.30806C3.42527 3.19085 3.58424 3.125 3.75 3.125H8.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Выйти
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
    <!-- Загружаем скрипт только если он еще не загружен -->
    <script>
        window.addEventListener('load', function() {
            if (typeof window.appScriptLoaded === 'undefined') {
                window.appScriptLoaded = true;
                
                try {
                    const script = document.createElement('script');
                    script.src = "/build/assets/app-eMHK6VFw.js";
                    script.async = true;
                    script.defer = true;
                    
                    script.onerror = function() {
                        console.error('Ошибка загрузки скрипта app-eMHK6VFw.js');
                        window.appScriptLoaded = false;
                    };
                    
                    document.head.appendChild(script);
                } catch (e) {
                    console.error('Ошибка при добавлении скрипта:', e);
                }
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchTrigger = document.querySelector('.search-trigger');
            if (searchTrigger) {
                searchTrigger.addEventListener('click', function() {
                    const container = this.closest('.search-container');
                    container.classList.toggle('active');
                    if (container.classList.contains('active')) {
                        const searchInput = container.querySelector('.search-input');
                        if (searchInput) {
                            searchInput.focus();
                        }
                    }
                });
            }

            // Закрытие поиска при клике вне формы
            document.addEventListener('click', function(event) {
                const container = document.querySelector('.search-container');
                if (container && !container.contains(event.target)) {
                    container.classList.remove('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html> 