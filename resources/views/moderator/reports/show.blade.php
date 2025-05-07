@extends('layouts.app')

@section('title', 'Просмотр жалобы #' . $complaint->id)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Жалоба #{{ $complaint->id }}</h1>
                <a href="{{ route('moderator.complaints.index') }}" class="btn btn-secondary">
                    Назад к списку
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- Информация о жалобе -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Информация о жалобе</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Тип жалобы:</strong>
                                <span class="badge bg-primary">{{ $complaint->type }}</span>
                                <span class="badge bg-secondary">{{ $complaint->target_type }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Причина:</strong>
                                <p class="mb-0">{{ $complaint->reason }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Отправитель:</strong>
                                <p class="mb-0">
                                    @if($complaint->user && $complaint->user->id)
                                        <a href="{{ route('users.show', $complaint->user) }}" class="text-decoration-none">
                                            {{ $complaint->user->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Удален</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>На пользователя:</strong>
                                <p class="mb-0">
                                    @if($complaint->complaintable && $complaint->complaintable->user && $complaint->complaintable->user->id)
                                        <a href="{{ route('users.show', $complaint->complaintable->user) }}" class="text-decoration-none">
                                            {{ $complaint->complaintable->user->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Удален</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>Дата отправки:</strong>
                                <p class="mb-0">{{ $complaint->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                            <div>
                                <strong>Статус:</strong>
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
                            </div>
                        </div>
                    </div>

                    <!-- Объект жалобы -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Объект жалобы</h5>
                        </div>
                        <div class="card-body">
                            @if($complaint->complaintable)
                                @if($complaint->target_type === 'post')
                                    <div class="post-content">
                                        <h6>Пост #{{ $complaint->complaintable->id }}</h6>
                                        <p>{{ $complaint->complaintable->content }}</p>
                                    </div>
                                @elseif($complaint->target_type === 'comment')
                                    <div class="comment-content">
                                        <h6>Комментарий #{{ $complaint->complaintable->id }}</h6>
                                        <p>{{ $complaint->complaintable->content }}</p>
                                    </div>
                                @else
                                    <div class="reply-content">
                                        <h6>Ответ #{{ $complaint->complaintable->id }}</h6>
                                        <p>{{ $complaint->complaintable->content }}</p>
                                    </div>
                                @endif
                            @else
                                <p class="text-muted">Объект был удален</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Форма обработки жалобы -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Обработка жалобы</h5>
                        </div>
                        <div class="card-body">
                            <form id="complaintForm" class="complaint-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="status" class="form-label">Статус</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="in_progress" {{ $complaint->status === 'in_progress' ? 'selected' : '' }}>В работе</option>
                                        <option value="resolved" {{ $complaint->status === 'resolved' ? 'selected' : '' }}>Решена</option>
                                        <option value="rejected" {{ $complaint->status === 'rejected' ? 'selected' : '' }}>Отклонена</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="moderator_comment" class="form-label">Комментарий модератора</label>
                                    <textarea class="form-control" id="moderator_comment" name="moderator_comment" rows="4" required>{{ $complaint->moderator_comment }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('complaintForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('{{ route("moderator.complaints.update", $complaint) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: formData.get('status'),
                moderator_comment: formData.get('moderator_comment')
            })
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Ошибка при обновлении жалобы');
        }

        // Показываем уведомление об успехе
        alert(result.message);
        
        // Перезагружаем страницу для обновления данных
        window.location.reload();
        
    } catch (error) {
        console.error('Ошибка:', error);
        alert(error.message || 'Произошла ошибка при обновлении жалобы');
    }
});
</script>
@endpush
@endsection 