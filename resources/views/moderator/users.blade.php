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
                <h1 class="mb-4">Пользователи</h1>
                
                <div class="card dashboard-card mb-4">
                    <div class="card-body">
                        <form action="{{ route('moderator.users') }}" method="GET" class="d-flex align-items-center">
                            <div class="search-box flex-grow-1 me-3">
                                <input type="text" name="search" class="form-control" placeholder="Поиск пользователей..." value="{{ request('search') }}">
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
                                        <th>Имя</th>
                                        <th>Email</th>
                                        <th>Дата регистрации</th>
                                        <th>Статус</th>
                                        <th>Жалобы</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->format('d.m.Y') }}</td>
                                            <td>
                                                @if($user->isRestricted())
                                                    <span class="badge bg-danger">Ограничен</span>
                                                @else
                                                    <span class="badge bg-success">Активен</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $totalComplaints = $user->complaints_count + $user->comment_complaints_count;
                                                @endphp
                                                @if($totalComplaints > 0)
                                                    @if($totalComplaints >= 1 && $totalComplaints <= 9)
                                                        <span class="badge bg-success">{{ $totalComplaints }}</span>
                                                    @elseif($totalComplaints >= 10 && $totalComplaints <= 15)
                                                        <span class="badge bg-warning">{{ $totalComplaints }}</span>
                                                    @elseif($totalComplaints >= 16 && $totalComplaints <= 20)
                                                        <span class="badge bg-danger">{{ $totalComplaints }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ $totalComplaints }}</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-action">Профиль</a>
                                                    @if(!$user->isRestricted())
                                                        <button type="button" class="btn btn-sm btn-action restrict-btn" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">Ограничить</button>
                                                    @endif
                                                    <form action="{{ route('moderator.users.delete', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-action" onclick="return confirm('Вы уверены?')">Удалить</button>
                                                    </form>
                                                </div>
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

<!-- Модальное окно ограничения пользователя -->
<div class="modal fade" id="restrictModal" tabindex="-1" aria-labelledby="restrictModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="restrictForm" method="POST" action="">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="restrictModalLabel">Ограничить пользователя</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Пользователь:</label>
            <span id="restrictUserName" class="fw-bold"></span>
          </div>
          <div class="mb-3">
            <label for="restrictDuration" class="form-label">На какой срок ограничить?</label>
            <select name="duration" id="restrictDuration" class="form-select" required>
              <option value="1">1 день</option>
              <option value="3">3 дня</option>
              <option value="7">1 неделя</option>
              <option value="30">1 месяц</option>
              <option value="forever">Навсегда</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Ограничить</button>
        </div>
      </div>
    </form>
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
    
    .btn-action {
        background: #1682FD !important;
        color: #fff !important;
        border: none !important;
        border-radius: 6px !important;
        font-weight: 500;
        transition: background 0.15s;
        box-shadow: 0 1px 2px rgba(22,130,253,0.07);
    }
    
    .btn-action:hover, .btn-action:focus {
        background: #1266c7 !important;
        color: #fff !important;
    }
    
    .d-flex.gap-2 > * {
        margin-right: 8px;
        margin-bottom: 4px;
    }
    
    .d-flex.gap-2 > *:last-child {
        margin-right: 0;
    }
</style>
@endpush
@push('scripts')
<script>
document.querySelectorAll('.restrict-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const userId = this.dataset.userId;
    const userName = this.dataset.userName;
    document.getElementById('restrictForm').action = '/moderator/users/' + userId + '/restrict';
    document.getElementById('restrictUserName').textContent = userName;
    new bootstrap.Modal(document.getElementById('restrictModal')).show();
  });
});

// AJAX ограничение пользователя и обновление таблицы
const restrictForm = document.getElementById('restrictForm');
if (restrictForm) {
    restrictForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const action = this.action;
        fetch(action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            return response.text();
        })
        .then(() => {
            // После успешного ограничения — обновить таблицу пользователей через AJAX
            fetch(window.location.href, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.querySelector('.table-responsive');
                    document.querySelector('.table-responsive').innerHTML = newTable.innerHTML;
                });
            // Закрыть модалку
            bootstrap.Modal.getInstance(document.getElementById('restrictModal')).hide();
        });
    });
}
</script>
@endpush
@endsection 