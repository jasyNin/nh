<div class="col-md-2 ps-0">
    <div class="side-menu">
        <div class="menu-section mb-4">
            @auth
                @if(auth()->user()->is_admin)
                    <div class="menu-item {{ Route::is('admin.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <img src="{{ asset('images/admin.svg') }}" class="me-2" width="24" height="24" alt="Админ панель">
                            Админ панель
                        </a>
                    </div>
                @endif
            @endauth
            <div class="menu-item {{ Route::is('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/home.svg') }}" class="me-2" width="24" height="24" alt="Лента">
                    Лента
                </a>
            </div>
            <div class="menu-item {{ Route::is('users.rating') ? 'active' : '' }}">
                <a href="{{ route('users.rating') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/rank.svg') }}" class="me-2" width="24" height="24" alt="Рейтинг">
                    Рейтинг
                </a>
            </div>
            <div class="menu-item {{ Route::is('tags.*') ? 'active' : '' }}">
                <a href="{{ route('tags.index') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/tag.svg') }}" class="me-2" width="24" height="24" alt="Теги">
                    Теги
                </a>
            </div>
            <div class="menu-item {{ Route::is('answers.*') ? 'active' : '' }}">
                <a href="{{ route('answers.index') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/ansvers.svg') }}" class="me-2" width="24" height="24" alt="Ответы">
                    Ответы
                </a>
            </div>
            @auth
            <div class="menu-item {{ Route::is('bookmarks.*') ? 'active' : '' }}">
                <a href="{{ route('bookmarks.index') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/bookmark.svg') }}" class="me-2" width="24" height="24" alt="Закладки">
                    Закладки
                </a>
            </div>
            <div class="menu-item {{ Route::is('drafts.*') ? 'active' : '' }}">
                <a href="{{ route('drafts.index') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/pen.svg') }}" class="me-2" width="24" height="24" alt="Черновики">
                    Черновики
                </a>
            </div>
            @endauth
        </div>

        <div class="menu-section mt-auto">
            <div class="menu-item {{ Route::is('rules') ? 'active' : '' }}">
                <a href="{{ route('rules') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/rules.svg') }}" class="me-2" width="24" height="24" alt="Правила">
                    Правила
                </a>
            </div>
            <div class="menu-item {{ Route::is('help') ? 'active' : '' }}">
                <a href="{{ route('help') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/help-circle.svg') }}" class="me-2" width="24" height="24" alt="Помощь">
                    Помощь
                </a>
            </div>
            <div class="menu-item {{ Route::is('about') ? 'active' : '' }}">
                <a href="{{ route('about') }}" class="d-flex align-items-center text-decoration-none">
                    <img src="{{ asset('images/info.svg') }}" class="me-2" width="24" height="24" alt="О проекте">
                    О проекте
                </a>
            </div>
        </div>
    </div>
</div> 