@extends('layouts.app')

@section('title', 'Управление жалобами')

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
                    <h1>Управление жалобами</h1>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(isset($error))
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endif
                
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Тип</th>
                                        <th>Объект</th>
                                        <th>Пользователь</th>
                                        <th>Статус</th>
                                        <th>Дата</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($complaints as $complaint)
                                        <tr>
                                            <td>{{ $complaint->id }}</td>
                                            <td>{{ $complaint->type }}</td>
                                            <td>
                                                @php
                                                    $type = str_replace('App\\Models\\', '', $complaint->complaintable_type);
                                                @endphp
                                                
                                                @if($complaint->complaintable)
                                                    @if($type === 'Post')
                                                        Пост #{{ $complaint->complaintable_id }}
                                                        @if(isset($complaint->complaintable->title))
                                                            - {{ Str::limit($complaint->complaintable->title, 30) }}
                                                        @endif
                                                    @elseif($type === 'Comment')
                                                        Комментарий #{{ $complaint->complaintable_id }}
                                                        @if(isset($complaint->complaintable->content))
                                                            - {{ Str::limit($complaint->complaintable->content, 30) }}
                                                        @endif
                                                    @elseif($type === 'CommentReply')
                                                        Ответ #{{ $complaint->complaintable_id }}
                                                        @if(isset($complaint->complaintable->content))
                                                            - {{ Str::limit($complaint->complaintable->content, 30) }}
                                                        @endif
                                                    @else
                                                        Объект #{{ $complaint->complaintable_id }}
                                                    @endif
                                                @else
                                                    <span class="text-danger">Объект удален</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($complaint->user)
                                                    {{ $complaint->user->name }}
                                                @else
                                                    <span class="text-danger">Пользователь удален</span>
                                                @endif
                                            </td>
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
                                            <td>{{ $complaint->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-sm btn-primary me-1">
                                                        Просмотреть
                                                    </a>
                                                    <form action="{{ route('admin.complaints.updateStatus', $complaint) }}" method="POST" class="d-inline complaint-form me-1" data-complaint-id="{{ $complaint->id }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="closed">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            Закрыть
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.complaints.destroy', $complaint) }}" method="POST" class="d-inline complaint-form" data-complaint-id="{{ $complaint->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            Удалить
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Жалоб не найдено</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $complaints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Обработка форм обновления статуса и удаления жалоб
        const forms = document.querySelectorAll('.complaint-form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const action = this.querySelector('button[type="submit"]').textContent.trim();
                const complaintId = this.dataset.complaintId;
                
                if (confirm(`Вы уверены, что хотите ${action.toLowerCase()} жалобу #${complaintId}?`)) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.875rem;
        padding: 0.5em 0.75em;
    }
    .btn-group {
        display: flex;
        gap: 0.25rem;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush
@endsection 