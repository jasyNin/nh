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
                <h1 class="mb-4">Управление жалобами</h1>
                
                <div class="card border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Тип</th>
                                        <th>Причина</th>
                                        <th>От пользователя</th>
                                        <th>Дата</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaints as $complaint)
                                    <tr id="complaint-row-{{ $complaint->id }}">
                                        <td>{{ $complaint->id }}</td>
                                        <td>{{ $complaint->type }}</td>
                                        <td>{{ Str::limit($complaint->reason, 50) }}</td>
                                        <td>{{ $complaint->user->name }}</td>
                                        <td>{{ $complaint->created_at->format('d.m.Y H:i') }}</td>
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
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.complaints.show', $complaint) }}" class="btn btn-sm btn-primary me-1">
                                                    Просмотреть
                                                </a>
                                                <form action="{{ route('admin.complaints.updateStatus', $complaint) }}" method="POST" class="d-inline complaint-form me-1" data-complaint-id="{{ $complaint->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="closed">
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Вы уверены, что хотите закрыть эту жалобу?')">
                                                        Закрыть
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.complaints.destroy', $complaint) }}" method="POST" class="d-inline delete-complaint-form" data-complaint-id="{{ $complaint->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту жалобу?')">
                                                        Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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
    // Получаем CSRF токен из мета-тега
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Находим все формы закрытия жалоб
    const complaintForms = document.querySelectorAll('.complaint-form');
    
    complaintForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const complaintId = this.dataset.complaintId;
            
            // Добавляем CSRF токен в заголовки запроса
            const headers = {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            };
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: headers,
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 419) {
                        // Если токен истек, перезагружаем страницу
                        window.location.reload();
                        return;
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!data) return; // Если ответ пустой (после перезагрузки)
                
                // Находим строку жалобы
                const complaintRow = document.getElementById(`complaint-row-${complaintId}`);
                
                // Добавляем класс для анимации исчезновения
                complaintRow.classList.add('fade-out');
                
                // Через 5 секунд удаляем строку
                setTimeout(() => {
                    complaintRow.remove();
                    
                    // Если это была последняя жалоба на странице, перезагружаем страницу
                    const remainingComplaints = document.querySelectorAll('tr[id^="complaint-row-"]');
                    if (remainingComplaints.length === 0) {
                        window.location.reload();
                    }
                }, 5000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при закрытии жалобы');
            });
        });
    });
    
    // Находим все формы удаления жалоб
    const deleteComplaintForms = document.querySelectorAll('.delete-complaint-form');
    
    deleteComplaintForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const complaintId = this.dataset.complaintId;
            
            // Добавляем CSRF токен в заголовки запроса
            const headers = {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            };
            
            fetch(this.action, {
                method: 'DELETE',
                headers: headers,
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 419) {
                        // Если токен истек, перезагружаем страницу
                        window.location.reload();
                        return;
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!data) return; // Если ответ пустой (после перезагрузки)
                
                // Находим строку жалобы
                const complaintRow = document.getElementById(`complaint-row-${complaintId}`);
                
                // Добавляем класс для анимации исчезновения
                complaintRow.classList.add('fade-out');
                
                // Через 5 секунд удаляем строку
                setTimeout(() => {
                    complaintRow.remove();
                    
                    // Если это была последняя жалоба на странице, перезагружаем страницу
                    const remainingComplaints = document.querySelectorAll('tr[id^="complaint-row-"]');
                    if (remainingComplaints.length === 0) {
                        window.location.reload();
                    }
                }, 5000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при удалении жалобы');
            });
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.fade-out {
    opacity: 1;
    transition: opacity 5s ease-out;
}

.fade-out:hover {
    opacity: 1;
}
</style>
@endpush
@endsection 