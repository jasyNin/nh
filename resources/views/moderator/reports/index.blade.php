@extends('layouts.app')

@section('title', 'Жалобы')

@section('content')
<div class="row">
    <div class="container" style="margin-top: 80px;">
        <div class="row">
            <!-- Боковое меню -->
            <x-side-menu />
            <x-side-menu-styles />
            
            <!-- Основной контент -->
            <div class="col-md-9">
                <h1 class="mb-4">Жалобы</h1>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Тип</th>
                                        <th>Объект</th>
                                        <th>Отправитель</th>
                                        <th>На пользователя</th>
                                        <th>Статус</th>
                                        <th>Дата</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaints as $complaint)
                                    <tr>
                                        <td>{{ $complaint->id }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $complaint->type }}</span>
                                            <span class="badge bg-secondary">{{ $complaint->target_type }}</span>
                                        </td>
                                        <td>
                                            @if($complaint->complaintable)
                                                @if($complaint->target_type === 'post')
                                                    Пост #{{ $complaint->complaintable->id }}
                                                @elseif($complaint->target_type === 'comment')
                                                    Комментарий #{{ $complaint->complaintable->id }}
                                                @else
                                                    Ответ #{{ $complaint->complaintable->id }}
                                                @endif
                                            @else
                                                <span class="text-muted">Удален</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($complaint->user && $complaint->user->id)
                                                <a href="{{ route('users.show', $complaint->user) }}" class="text-decoration-none">
                                                    {{ $complaint->user->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Удален</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($complaint->complaintable && $complaint->complaintable->user && $complaint->complaintable->user->id)
                                                <a href="{{ route('users.show', $complaint->complaintable->user) }}" class="text-decoration-none">
                                                    {{ $complaint->complaintable->user->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Удален</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($complaint->status)
                                                @case('new')
                                                    <span class="badge bg-danger">Новая</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge bg-warning">В работе</span>
                                                    @break
                                                @case('resolved')
                                                    <span class="badge bg-success">Решена</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-secondary">Отклонена</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $complaint->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('moderator.complaints.show', $complaint) }}" class="btn btn-sm btn-primary">
                                                Просмотр
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $complaints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 