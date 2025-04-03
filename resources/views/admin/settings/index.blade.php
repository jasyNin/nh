@extends('layouts.app')

@section('title', 'Настройки сайта')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <div class="admin-dashboard">
                <h1 class="mb-4">Настройки сайта</h1>
                
                <div class="card border-0">
                    <div class="card-body">
                        <form action="#" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Название сайта</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" value="{{ $settings['site_name'] ?? 'Good' }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="site_description" class="form-label">Описание сайта</label>
                                <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ $settings['site_description'] ?? '' }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Контактный email</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="posts_per_page" class="form-label">Постов на странице</label>
                                <input type="number" class="form-control" id="posts_per_page" name="posts_per_page" value="{{ $settings['posts_per_page'] ?? 10 }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="allow_registration" class="form-label">Разрешить регистрацию</label>
                                <select class="form-select" id="allow_registration" name="allow_registration">
                                    <option value="1" {{ ($settings['allow_registration'] ?? 1) == 1 ? 'selected' : '' }}>Да</option>
                                    <option value="0" {{ ($settings['allow_registration'] ?? 1) == 0 ? 'selected' : '' }}>Нет</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="maintenance_mode" class="form-label">Режим обслуживания</label>
                                <select class="form-select" id="maintenance_mode" name="maintenance_mode">
                                    <option value="0" {{ ($settings['maintenance_mode'] ?? 0) == 0 ? 'selected' : '' }}>Выключен</option>
                                    <option value="1" {{ ($settings['maintenance_mode'] ?? 0) == 1 ? 'selected' : '' }}>Включен</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Сохранить настройки</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 