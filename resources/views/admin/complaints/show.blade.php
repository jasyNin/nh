@extends('layouts.app')

@section('title', 'Просмотр жалобы')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <div class="admin-dashboard">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Просмотр жалобы #{{ $complaint->id }}</h1>
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary">
                        Назад к списку
                    </a>
                </div>
                
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">Информация о жалобе</h5>
                                <table class="table">
                                    <tr>
                                        <th>ID:</th>
                                        <td>{{ $complaint->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Тип:</th>
                                        <td>{{ $complaint->type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Статус:</th>
                                        <td>
                                            @if($complaint->status === 'new')
                                                <span class="badge bg-primary">Новая</span>
                                            @elseif($complaint->status === 'open')
                                                <span class="badge bg-warning">Открыт спор</span>
                                            @elseif($complaint->status === 'unjustified')
                                                <span class="badge bg-danger">Не обоснована</span>
                                            @elseif($complaint->status === 'closed')
                                                <span class="badge bg-success">Закрыта</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Дата создания:</th>
                                        <td>{{ $complaint->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title">Информация о пользователе</h5>
                                <table class="table">
                                    <tr>
                                        <th>ID:</th>
                                        <td>{{ $complaint->user->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Имя:</th>
                                        <td>{{ $complaint->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $complaint->user->email }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5 class="card-title">Причина жалобы</h5>
                            <div class="p-3 bg-light rounded">
                                {{ $complaint->reason }}
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5 class="card-title">Содержание жалобы</h5>
                            <div class="p-3 bg-light rounded">
                                @if($complaint->complaintable_type === 'App\\Models\\Post')
                                    <h6>Пост:</h6>
                                    <p>{{ $complaint->complaintable->content }}</p>
                                    <a href="{{ $complaint->complaintable->getUrl() }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                        Перейти к посту
                                    </a>
                                @elseif($complaint->complaintable_type === 'App\\Models\\Comment')
                                    <h6>Комментарий:</h6>
                                    <p>{{ $complaint->complaintable->content }}</p>
                                    <a href="{{ $complaint->complaintable->getUrl() }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                        Перейти к комментарию
                                    </a>
                                @elseif($complaint->complaintable_type === 'App\\Models\\CommentReply')
                                    <h6>Ответ на комментарий:</h6>
                                    <p>{{ $complaint->complaintable->content }}</p>
                                    <div class="mt-3">
                                        <h6>Комментарий, на который дан ответ:</h6>
                                        <p>{{ $complaint->complaintable->comment->content }}</p>
                                    </div>
                                    <a href="{{ $complaint->complaintable->getUrl() }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                        Перейти к ответу
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5 class="card-title">Действия</h5>
                            <div class="btn-group">
                                <form action="{{ route('admin.complaints.updateStatus', $complaint) }}" method="POST" class="d-inline me-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="open">
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Вы уверены, что хотите открыть спор по этой жалобе?')">
                                        Открыть спор
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.complaints.updateStatus', $complaint) }}" method="POST" class="d-inline me-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="unjustified">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите отметить жалобу как необоснованную?')">
                                        Отметить как необоснованную
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.complaints.updateStatus', $complaint) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="closed">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Вы уверены, что хотите закрыть эту жалобу?')">
                                        Закрыть жалобу
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 