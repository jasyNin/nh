@extends('layouts.app')

@section('title', 'Помощь')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10 mb-5" style="margin-bottom: 60px;">
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

            <!-- Секция рангов -->
            <div class="mt-5">
                <div class="text-center mb-5">
                    <h2 class="display-5 fw-light mb-3">Система рангов</h2>
                    <p class="lead text-muted">Узнайте о рангах и их преимуществах</p>
                </div>

                <!-- Описание системы рангов -->
                <div class="card mb-5" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <div class="card-body p-4">
                        <h4 class="fw-light mb-4">О системе рангов</h4>
                        <p class="text-muted mb-0">
                            Ранги отражают ваш вклад в сообщество. Чем больше качественных ответов вы даете и чем больше лайков получаете, тем выше ваш ранг. 
                            Каждый ранг открывает новые возможности и показывает ваш авторитет в сообществе. 
                            Специальные роли (администратор, модератор) назначаются администрацией за особые заслуги и активное участие в жизни сообщества.
                        </p>
                    </div>
                </div>

                <!-- Иерархическая лестница рангов -->
                <div class="ranks-ladder mb-5">
                    <!-- Сверхразум -->
                    <div class="rank-item mb-4" style="background: linear-gradient(135deg, #CC0000, #990000); border-radius: 15px; padding: 25px; position: relative; box-shadow: 0 8px 20px rgba(204, 0, 0, 0.3); transition: transform 0.3s ease;">
                        <div class="d-flex align-items-center">
                            <div class="rank-icon-wrapper" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 10px; margin-right: 25px;">
                                <img src="{{ asset('images/ruby.svg') }}" alt="Сверхразум" style="width: 80px; height: 80px; border: 5px solid white; border-radius: 50%;">
                            </div>
                            <div>
                                <h4 class="fw-light text-white mb-2" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">Сверхразум</h4>
                                <div class="mb-2">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;" repeat="6">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                </div>
                                <p class="text-white mb-1" style="font-size: 1.1rem; opacity: 0.9;">240+ баллов</p>
                                <p class="small text-white mb-0" style="opacity: 0.8;">Вы достигли вершины знаний и являетесь примером для всего сообщества.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Эксперт -->
                    <div class="rank-item mb-4" style="background: linear-gradient(135deg, #008B8B, #006666); border-radius: 15px; padding: 25px; position: relative; box-shadow: 0 8px 20px rgba(0, 139, 139, 0.3); transition: transform 0.3s ease;">
                        <div class="d-flex align-items-center">
                            <div class="rank-icon-wrapper" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 10px; margin-right: 25px;">
                                <img src="{{ asset('images/dimond.svg') }}" alt="Эксперт" style="width: 80px; height: 80px; border: 5px solid white; border-radius: 50%;">
                            </div>
                            <div>
                                <h4 class="fw-light text-white mb-2" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">Эксперт</h4>
                                <div class="mb-2">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                </div>
                                <p class="text-white mb-1" style="font-size: 1.1rem; opacity: 0.9;">120-239 баллов</p>
                                <p class="small text-white mb-0" style="opacity: 0.8;">Вы - один из лучших специалистов в сообществе, к вашим ответам прислушиваются.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Эрудит -->
                    <div class="rank-item mb-4" style="background: linear-gradient(135deg, #660066, #4D004D); border-radius: 15px; padding: 25px; position: relative; box-shadow: 0 8px 20px rgba(102, 0, 102, 0.3); transition: transform 0.3s ease;">
                        <div class="d-flex align-items-center">
                            <div class="rank-icon-wrapper" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 10px; margin-right: 25px;">
                                <img src="{{ asset('images/purple.svg') }}" alt="Эрудит" style="width: 80px; height: 80px; border: 5px solid white; border-radius: 50%;">
                            </div>
                            <div>
                                <h4 class="fw-light text-white mb-2" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">Эрудит</h4>
                                <div class="mb-2">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                </div>
                                <p class="text-white mb-1" style="font-size: 1.1rem; opacity: 0.9;">60-119 баллов</p>
                                <p class="small text-white mb-0" style="opacity: 0.8;">Ваши знания и опыт помогают развиваться всему сообществу.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Знаток -->
                    <div class="rank-item mb-4" style="background: linear-gradient(135deg, #DAA520, #B8860B); border-radius: 15px; padding: 25px; position: relative; box-shadow: 0 8px 20px rgba(218, 165, 32, 0.3); transition: transform 0.3s ease;">
                        <div class="d-flex align-items-center">
                            <div class="rank-icon-wrapper" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 10px; margin-right: 25px;">
                                <img src="{{ asset('images/gold.svg') }}" alt="Знаток" style="width: 80px; height: 80px; border: 5px solid white; border-radius: 50%;">
                            </div>
                            <div>
                                <h4 class="fw-light text-white mb-2" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">Знаток</h4>
                                <div class="mb-2">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                </div>
                                <p class="text-white mb-1" style="font-size: 1.1rem; opacity: 0.9;">30-59 баллов</p>
                                <p class="small text-white mb-0" style="opacity: 0.8;">Ваши ответы ценятся сообществом, вы становитесь авторитетом в своей области.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ученик -->
                    <div class="rank-item mb-4" style="background: linear-gradient(135deg, #A9A9A9, #808080); border-radius: 15px; padding: 25px; position: relative; box-shadow: 0 8px 20px rgba(169, 169, 169, 0.3); transition: transform 0.3s ease;">
                        <div class="d-flex align-items-center">
                            <div class="rank-icon-wrapper" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 10px; margin-right: 25px;">
                                <img src="{{ asset('images/silver.svg') }}" alt="Ученик" style="width: 80px; height: 80px; border: 5px solid white; border-radius: 50%;">
                            </div>
                            <div>
                                <h4 class="fw-light text-white mb-2" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">Ученик</h4>
                                <div class="mb-2">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                </div>
                                <p class="text-white mb-1" style="font-size: 1.1rem; opacity: 0.9;">10-29 баллов</p>
                                <p class="small text-white mb-0" style="opacity: 0.8;">Вы уже освоили основы и начинаете помогать другим участникам.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Новичок -->
                    <div class="rank-item" style="background: linear-gradient(135deg, #7B3F00, #5A2D00); border-radius: 15px; padding: 25px; position: relative; box-shadow: 0 8px 20px rgba(123, 63, 0, 0.3); transition: transform 0.3s ease;">
                        <div class="d-flex align-items-center">
                            <div class="rank-icon-wrapper" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 10px; margin-right: 25px;">
                                <img src="{{ asset('images/novichec.svg') }}" alt="Новичок" style="width: 80px; height: 80px; border: 5px solid white; border-radius: 50%;">
                            </div>
                            <div>
                                <h4 class="fw-light text-white mb-2" style="font-size: 1.5rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">Новичок</h4>
                                <div class="mb-2">
                                    <img src="{{ asset('images/star.png') }}" alt="star" style="width: 24px;">
                                </div>
                                <p class="text-white mb-1" style="font-size: 1.1rem; opacity: 0.9;">0-9 баллов</p>
                                <p class="small text-white mb-0" style="opacity: 0.8;">Начальный ранг для всех новых участников. Время учиться и задавать вопросы!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Специальные роли -->
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="card h-100" style="border: none;">
                            <div class="card-body text-center p-4">
                                <img src="{{ asset('images/adminicon.svg') }}" alt="Администратор" class="mb-3" style="width: 80px; height: 80px;">
                                <h4 class="fw-light mb-3">Администратор</h4>
                                <p class="text-muted">Управляет сайтом и имеет полный доступ ко всем функциям. Отвечает за развитие сообщества и поддержание порядка.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100" style="border: none;">
                            <div class="card-body text-center p-4">
                                <img src="{{ asset('images/moder.svg') }}" alt="Модератор" class="mb-3" style="width: 80px; height: 80px;">
                                <h4 class="fw-light mb-3">Модератор</h4>
                                <p class="text-muted">Следит за порядком, проверяет контент на соответствие правилам и помогает пользователям.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100" style="border: none;">
                            <div class="card-body text-center p-4">
                                <img src="{{ asset('images/bot.svg') }}" alt="Бот Нейрончик" class="mb-3" style="width: 80px; height: 80px;">
                                <h4 class="fw-light mb-3">Бот Нейрончик</h4>
                                <p class="text-muted">Искусственный интеллект, который помогает пользователям находить ответы и решать задачи.</p>
                            </div>
                        </div>
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