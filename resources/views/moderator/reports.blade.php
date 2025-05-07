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
                <h1 class="mb-4">Жалобы</h1>
                
                <div class="card dashboard-card mb-4">
                    <div class="card-body">
                        <form action="{{ route('moderator.reports') }}" method="GET" class="d-flex align-items-center">
                            <div class="search-box flex-grow-1 me-3">
                                <input type="text" name="search" class="form-control" placeholder="Поиск по жалобам..." value="{{ request('search') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Поиск</button>
                        </form>
                    </div>
                </div>

                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Пользователь</th>
                                        <th>Тип</th>
                                        <th>Причина</th>
                                        <th>Статус</th>
                                        <th>Дата</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>{{ $report->id }}</td>
                                            <td>{{ $report->user->name }}</td>
                                            <td>{{ $report->reportable_type }}</td>
                                            <td>{{ $report->reason }}</td>
                                            <td>
                                                <span class="badge bg-{{ $report->status === 'pending' ? 'warning' : 'success' }}">
                                                    {{ $report->status }}
                                                </span>
                                            </td>
                                            <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                @if($report->status === 'pending')
                                                    <form action="{{ route('moderator.reports.resolve', $report) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">Обработать</button>
                                                    </form>
                                                @endif
                                                
                                                @if($report->reportable_type === 'App\Models\Post')
                                                    <form action="{{ route('moderator.posts.hide', $report->reportable) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning">Скрыть пост</button>
                                                    </form>
                                                @endif
                                                
                                                @if($report->reportable_type === 'App\Models\Comment')
                                                    <form action="{{ route('moderator.comments.hide', $report->reportable) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning">Скрыть комментарий</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    
    .search-box {
        position: relative;
    }
    
    .search-box input {
        padding-left: 2.5rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #2d3748;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }
    
    .btn-primary {
        background: #1682FD;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection 