@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container" style="margin-top: 80px;">
        <div class="row">
            <!-- Боковое меню -->
            <x-side-menu />
            <x-side-menu-styles />
            
            <!-- Основной контент -->
            <div class="col-md-9">
            <h1 class="mb-4">Панель управления</h1>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 dashboard-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box reports-icon">
                                        <i class="fas fa-flag"></i>
                                    </div>
                                    <h5 class="card-title mb-0 ms-3">Жалобы</h5>
                                </div>
                                <p class="card-text text-muted">Управление жалобами на контент и пользователей</p>
                                <a href="{{ route('moderator.complaints.index') }}" class="btn btn-primary w-100">Перейти к жалобам</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card h-100 dashboard-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box users-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h5 class="card-title mb-0 ms-3">Пользователи</h5>
                                </div>
                                <p class="card-text text-muted">Управление пользователями и их правами</p>
                                <a href="{{ route('moderator.users') }}" class="btn btn-primary w-100">Перейти к пользователям</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card h-100 dashboard-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-box content-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h5 class="card-title mb-0 ms-3">Контент</h5>
                                </div>
                                <p class="card-text text-muted">Управление постами и комментариями</p>
                                <a href="{{ route('moderator.content') }}" class="btn btn-primary w-100">Перейти к контенту</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Статистика</h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="stat-box">
                                            <div class="stat-icon reports-icon">
                                                <i class="fas fa-flag"></i>
                                            </div>
                                            <div class="stat-info">
                                                <h3>{{ $totalComplaintsCount }}</h3>
                                                <p>Всего жалоб</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-box">
                                            <div class="stat-icon users-icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div class="stat-info">
                                                <h3>{{ $users->count() }}</h3>
                                                <p>Всего пользователей</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-box">
                                            <div class="stat-icon content-icon">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div class="stat-info">
                                                <h3>{{ $posts->count() }}</h3>
                                                <p>Всего постов</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-box">
                                            <div class="stat-icon comments-icon">
                                                <i class="fas fa-comments"></i>
                                            </div>
                                            <div class="stat-info">
                                                <h3>{{ $comments->count() }}</h3>
                                                <p>Всего комментариев</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .container {
        max-width: 1300px;
    }
    
    .dashboard-card {
        border: none;
        border-radius: 15px;
    }
    
    .icon-box {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
    }
    
    .reports-icon {
        background: linear-gradient(45deg, #ff6b6b, #ff8787);
    }
    
    .users-icon {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }
    
    .content-icon {
        background: linear-gradient(45deg, #20c997, #1a9f7a);
    }
    
    .comments-icon {
        background: linear-gradient(45deg, #6f42c1, #563d7c);
    }
    
    .stat-box {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-right: 1rem;
    }
    
    .stat-info h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: bold;
        color: #2d3748;
    }
    
    .stat-info p {
        margin: 0;
        color: #718096;
        font-size: 0.875rem;
    }
    
    .btn-primary {
        background: #1682FD;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }
</style>
@endpush
@endsection 