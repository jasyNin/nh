@extends('layouts.app')

@section('title', 'Правила')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <!-- Главный баннер -->
            <div class="text-center mb-5">
                <img src="{{ asset('images/rules.svg') }}" alt="Правила" class="mb-3" style="width: 64px; height: 64px; filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);">
                <h1 class="display-5 fw-light mb-3">Правила сообщества</h1>
                <p class="lead text-muted">Соблюдайте правила для создания комфортной атмосферы</p>
            </div>

            <!-- Правила -->
            <div class="row g-4">
                <!-- Общие правила -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-users me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Общие правила</h5>
                        </div>
                        <p class="text-muted mb-0">Уважайте других пользователей, не допускайте оскорблений и дискриминации. Создавайте качественный контент и помогайте другим участникам сообщества.</p>
                    </div>
                </div>

                <!-- Правила публикации -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-pen me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Правила публикации</h5>
                        </div>
                        <p class="text-muted mb-0">Публикуйте только уникальный контент, не копируйте чужие материалы. Используйте понятные заголовки и добавляйте релевантные теги.</p>
                    </div>
                </div>

                <!-- Правила комментирования -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-comments me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Правила комментирования</h5>
                        </div>
                        <p class="text-muted mb-0">Комментарии должны быть по существу и содержать полезную информацию. Не допускаются спам, реклама и оффтоп.</p>
                    </div>
                </div>

                <!-- Правила модерации -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-shield-alt me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Правила модерации</h5>
                        </div>
                        <p class="text-muted mb-0">Модераторы вправе удалять контент, нарушающий правила, и применять санкции к пользователям. Решения модераторов можно обжаловать.</p>
                    </div>
                </div>

                <!-- Запрещенный контент -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-ban me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Запрещенный контент</h5>
                        </div>
                        <p class="text-muted mb-0">Запрещено публиковать контент, содержащий насилие, порнографию, экстремизм, пропаганду наркотиков и другие материалы, нарушающие законодательство.</p>
                    </div>
                </div>

                <!-- Ответственность -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-exclamation-triangle me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Ответственность</h5>
                        </div>
                        <p class="text-muted mb-0">Пользователи несут ответственность за свой контент. При нарушении правил может быть применен бан или ограничение доступа к функционалу.</p>
                    </div>
                </div>
            </div>

            <!-- Дополнительная информация -->
            <div class="text-center mt-5">
                <p class="text-muted mb-4">Остались вопросы по правилам?</p>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-2" style="color: #1682FD;"></i>Связаться с администрацией
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 