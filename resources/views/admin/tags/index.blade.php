@extends('layouts.app')

@section('title', 'Управление тегами')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <div class="admin-dashboard">
                <h1 class="mb-4">Управление тегами</h1>
                
                <div class="card border-0">
                    <div class="card-body">
                        <!-- Поле поиска -->
                        <div class="mb-4">
                            <div class="search-container position-relative" style="width: 300px;">
                                <input type="text" id="tagSearch" class="form-control" placeholder="Поиск тега..." style="border-radius: 20px; padding: 8px 40px 8px 16px; font-size: 0.9rem; border: 1px solid #e0e0e0; background-color: white;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); filter: invert(32%) sepia(98%) saturate(1234%) hue-rotate(210deg) brightness(97%) contrast(101%);">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Название</th>
                                        <th>Описание</th>
                                        <th>Количество постов</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tags as $tag)
                                    <tr>
                                        <td>{{ $tag->id }}</td>
                                        <td>{{ $tag->name }}</td>
                                        <td>{{ $tag->description }}</td>
                                        <td>{{ $tag->posts_count }}</td>
                                        <td>
                                            <a href="{{ route('tags.show', $tag) }}" class="btn btn-sm btn-primary me-2" target="_blank">
                                                Посмотреть
                                            </a>
                                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот тег?')">Удалить</button>
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

@push('styles')
<!-- Стили для страницы тегов в админ-панели перенесены в общий файл CSS app.css -->
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('tagSearch');
    const tagRows = document.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        tagRows.forEach(row => {
            const tagName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            
            if (tagName.includes(searchTerm)) {
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