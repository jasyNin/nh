@extends('layouts.app')

@section('title', 'Управление постами')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <div class="admin-dashboard">
                <h1 class="mb-4">Управление постами</h1>

                <div class="card border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Заголовок</th>
                                        <th>Автор</th>
                                        <th>Дата создания</th>
                                        <th>Жалобы</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->user->name }}</td>
                                        <td>{{ $post->created_at->format('d.m.Y H:i') }}</td>
                                        <td>{{ $post->complaints_count }}</td>
                                        <td>
                                            <a href="{{ $post->getUrl() }}" class="btn btn-sm btn-primary me-2" target="_blank">
                                                Посмотреть
                                            </a>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот пост?')">
                                                    Удалить
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 