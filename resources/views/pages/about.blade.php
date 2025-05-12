@extends('layouts.app')

@section('title', 'О проекте')

@section('content')
<div class="container main-content-container">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10" style="margin-bottom: 40px;">
            <!-- Главный баннер -->
            <div class="text-center mb-5">
                <img src="{{ asset('images/logo.svg') }}" alt="Логотип" class="mb-4" width="100" height="100">
                <h1 class="display-5 fw-light mb-3">Добро пожаловать в наше сообщество</h1>
                <p class="lead text-muted">Мы создаем платформу для обмена знаниями и опытом</p>
            </div>

            <!-- Основная информация -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-users fa-lg text-primary me-3"></i>
                            <h5 class="mb-0 fw-light">О нас</h5>
                        </div>
                        <p class="text-muted mb-0">Мы создаем платформу для обмена знаниями и опытом. Наша цель - помочь людям находить ответы на вопросы и делиться своими знаниями с другими.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-bullseye fa-lg text-primary me-3"></i>
                            <h5 class="mb-0 fw-light">Наша миссия</h5>
                        </div>
                        <p class="text-muted mb-0">Сделать знания доступными для всех, создать сообщество единомышленников и помочь каждому реализовать свой потенциал.</p>
                    </div>
                </div>
            </div>

            <!-- Что мы предлагаем и ценности -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-gift fa-lg text-primary me-3"></i>
                            <h5 class="mb-0 fw-light">Что мы предлагаем</h5>
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <span class="text-muted">Возможность задавать вопросы и получать ответы</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <span class="text-muted">Обмен опытом и знаниями</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                <span class="text-muted">Создание сообществ по интересам</span>
                            </li>
                            <li>
                                <i class="fas fa-check text-success me-2"></i>
                                <span class="text-muted">Систему рейтинга и репутации</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-4 bg-white rounded-3 shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-heart fa-lg text-primary me-3"></i>
                            <h5 class="mb-0 fw-light">Наши ценности</h5>
                        </div>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-star text-warning me-2"></i>
                                <span class="text-muted">Качество и достоверность информации</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-star text-warning me-2"></i>
                                <span class="text-muted">Уважение к каждому участнику</span>
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-star text-warning me-2"></i>
                                <span class="text-muted">Открытость и прозрачность</span>
                            </li>
                            <li>
                                <i class="fas fa-star text-warning me-2"></i>
                                <span class="text-muted">Постоянное развитие и улучшение</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Местоположение -->
            <div class="p-4 bg-white rounded-3 shadow-sm mb-5">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-map-marker-alt fa-lg text-primary me-3"></i>
                    <h5 class="mb-0 fw-light">Наше местоположение</h5>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="ratio ratio-16x9 mb-3">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2451.1234567890123!2d65.345678!3d55.456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x43b0c0c0c0c0c0c0%3A0x0!2z0JrRgNCw0YHQvdC-0L0g0J7QstC10YDRgdC60LDRjyDQvtCx0LvQsNGB0YLRjA!5e0!3m2!1sru!2sru!4v1234567890123!5m2!1sru!2sru" 
                                width="100%" 
                                height="300" 
                                style="border:0; border-radius: 14px;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-white rounded-3">
                            <h6 class="mb-3 fw-light">Контактная информация</h6>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-3"></i>
                                <span class="text-muted small">640006, Курганская Область, г.о. Город Курган, г Курган, ул Куйбышева, д. 144, стр. 21, помещ. 8</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-phone text-primary me-3"></i>
                                <span class="text-muted small">+7 (3522) 45-67-89</span>
                            </div>
                            <div class="d-flex align-items-center mb-5">
                                <i class="fas fa-envelope text-primary me-3"></i>
                                <span class="text-muted small">info@example.com</span>
                            </div>
                            <div class="text-center mt-5">
                                <img src="{{ asset('images/Startset.svg') }}" alt="StartSet" class="img-fluid" style="width: 200px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Руководители -->
            <div class="p-4 bg-white rounded-3 shadow-sm">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-user-tie fa-lg text-primary me-3"></i>
                    <h5 class="mb-0 fw-light">Руководители</h5>
                </div>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-4">
                        <img src="{{ asset('images/GJhgsGzgBQA.jpg') }}" alt="Слащева Т.С." class="rounded-circle" width="80" height="80">
                    </div>
                    <div>
                        <h6 class="mb-1 fw-light">Слащева Татьяна Сергеевна</h6>
                        <p class="text-muted mb-0">Генеральный Директор</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 