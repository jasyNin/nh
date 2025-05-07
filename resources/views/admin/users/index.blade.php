@extends('layouts.app')

@section('title', 'Управление пользователями')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <div class="admin-dashboard">
                <h1 class="mb-4">Управление пользователями</h1>
                
                <!-- Форма поиска и фильтров -->
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="search-container position-relative">
                                    <input type="text" 
                                           id="userSearch" 
                                           class="form-control" 
                                           placeholder="Поиск по имени или email" 
                                           style="border-radius: 20px; padding: 8px 40px 8px 16px; font-size: 0.9rem; border: 1px solid #e0e0e0; background-color: white;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); filter: invert(32%) sepia(98%) saturate(1234%) hue-rotate(210deg) brightness(97%) contrast(101%);">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-select" onchange="this.form.submit()">
                                    <option value="">Все роли</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Администраторы</option>
                                    <option value="moderator" {{ request('role') === 'moderator' ? 'selected' : '' }}>Модераторы</option>
                                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Пользователи</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>По дате регистрации</option>
                                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>По имени</option>
                                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>По рейтингу</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Имя</th>
                                        <th>Email</th>
                                        <th>Дата регистрации</th>
                                        <th>Статус</th>
                                        <th>Роль</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            @if($user->trashed())
                                                <span class="badge bg-danger">Удален</span>
                                            @else
                                                <span class="badge bg-success">Активен</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="user" {{ !$user->is_admin && !$user->is_moderator ? 'selected' : '' }}>Пользователь</option>
                                                    <option value="moderator" {{ $user->is_moderator ? 'selected' : '' }}>Модератор</option>
                                                    <option value="admin" {{ $user->is_admin ? 'selected' : '' }}>Администратор</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-primary me-2" target="_blank">
                                                Посмотреть
                                            </a>
                                            @if($user->trashed())
                                                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Восстановить</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">Удалить</button>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    const userRows = document.querySelectorAll('#usersTableBody tr');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        userRows.forEach(row => {
            const userName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const userEmail = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            
            if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection 