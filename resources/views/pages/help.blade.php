@extends('layouts.app')

@section('title', 'Помощь')

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
                <img src="{{ asset('images/help-circle.svg') }}" alt="Помощь" class="mb-3" style="width: 64px; height: 64px; filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);">
                <h1 class="display-5 fw-light mb-3">Помощь</h1>
                <p class="lead text-muted">Найдите ответы на часто задаваемые вопросы</p>
            </div>

            <!-- FAQ секция -->
            <div class="row g-4">
                <!-- Как задать вопрос -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-question me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Как задать вопрос?</h5>
                        </div>
                        <p class="text-muted mb-0">Нажмите на кнопку "Написать" в верхнем меню, в форме создания поста выберите в выподающем списке "Вопрос". Заполните форму, добавьте теги и отправьте. Ваш вопрос будет опубликован.</p>
                    </div>
                </div>

                <!-- Как ответить на вопрос -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-reply me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Как ответить на вопрос?</h5>
                        </div>
                        <p class="text-muted mb-0">Найдите интересующий вас вопрос и нажмите кнопку "Ответить". Напишите свой ответ и отправьте. Ответы также проходят модерацию.</p>
                    </div>
                </div>

                <!-- Как получить рейтинг -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-star me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Как получить рейтинг?</h5>
                        </div>
                        <p class="text-muted mb-0">Рейтинг начисляется за качественные ответы, которые получают положительные оценки от других пользователей. Чем больше полезных ответов, тем выше рейтинг.</p>
                    </div>
                </div>

                <!-- Как использовать теги -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-tags me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Как использовать теги?</h5>
                        </div>
                        <p class="text-muted mb-0">При создании вопроса добавьте релевантные теги через запятую. Это поможет другим пользователям найти ваш вопрос и увеличит шансы получить ответ.</p>
                    </div>
                </div>

                <!-- Как сообщить о нарушении -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-flag me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Как сообщить о нарушении?</h5>
                        </div>
                        <p class="text-muted mb-0">Нажмите на кнопку "Пожаловаться" под контентом, который нарушает правила. Опишите причину жалобы, и модераторы рассмотрят её в кратчайшие сроки.</p>
                    </div>
                </div>

                <!-- Как редактировать профиль -->
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user-edit me-3" style="color: #1682FD;"></i>
                            <h5 class="mb-0 fw-light">Как редактировать профиль?</h5>
                        </div>
                        <p class="text-muted mb-0">Перейдите в свой профиль и нажмите кнопку "Редактировать". Здесь вы можете изменить информацию о себе, добавить аватар и настроить уведомления.</p>
                    </div>
                </div>
            </div>

            <!-- Дополнительная помощь -->
            <div class="text-center mt-5">
                <p class="text-muted mb-4">Не нашли ответ на свой вопрос?</p>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-2" style="color: #1682FD;"></i>Связаться с поддержкой
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 