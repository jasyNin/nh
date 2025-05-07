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

                <!-- Форма поиска и фильтров -->
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="search-container position-relative">
                                    <input type="text" 
                                           id="postSearch" 
                                           class="form-control" 
                                           placeholder="Поиск по заголовку или автору" 
                                           style="border-radius: 20px; padding: 8px 40px 8px 16px; font-size: 0.9rem; border: 1px solid #e0e0e0; background-color: white;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); filter: invert(32%) sepia(98%) saturate(1234%) hue-rotate(210deg) brightness(97%) contrast(101%);">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>По дате создания</option>
                                    <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>По заголовку</option>
                                    <option value="views_count" {{ request('sort') === 'views_count' ? 'selected' : '' }}>По просмотрам</option>
                                    <option value="likes_count" {{ request('sort') === 'likes_count' ? 'selected' : '' }}>По лайкам</option>
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
                                        <th>Заголовок</th>
                                        <th>Автор</th>
                                        <th>Лайки</th>
                                        <th>Комментарии</th>
                                        <th>Дата создания</th>
                                        <th>Жалобы</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody id="postsTableBody">
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>{{ $post->title }}</td>
                                        <td>{{ $post->user->name }}</td>
                                        <td>{{ $post->likes()->count() }}</td>
                                        <td>{{ $post->comments()->count() }}</td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('postSearch');
    const postRows = document.querySelectorAll('#postsTableBody tr');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        postRows.forEach(row => {
            const postTitle = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const postAuthor = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            
            if (postTitle.includes(searchTerm) || postAuthor.includes(searchTerm)) {
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