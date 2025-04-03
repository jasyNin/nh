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
                                <tbody>
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
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 