@extends('layouts.app')

@section('title', 'Теги')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <div class="card border-0 bg-transparent">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2" style="filter: invert(32%) sepia(98%) saturate(1234%) hue-rotate(210deg) brightness(97%) contrast(101%);">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <h5 class="mb-0">Все теги</h5>
                    </div>
                    <div class="search-container position-relative" style="width: 300px;">
                        <input type="text" id="tagSearch" class="form-control" placeholder="Поиск тега..." style="border-radius: 20px; padding: 8px 40px 8px 16px; font-size: 0.9rem; border: 1px solid #e0e0e0; background-color: white;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); filter: invert(32%) sepia(98%) saturate(1234%) hue-rotate(210deg) brightness(97%) contrast(101%);">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                </div>
                <div class="card-body">
                    @if(request()->has('tag'))
                        @php
                            $selectedTag = $tags->where('slug', request()->tag)->first();
                        @endphp
                        @if($selectedTag && $selectedTag->posts_count == 0)
                            <div class="text-center py-5">
                                <img src="{{ asset('images/tag.svg') }}" class="mb-3" width="48" height="48" alt="Теги" style="filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);">
                                <h5 class="fw-light">Постов с тегом #{{ $selectedTag->name }} пока нет</h5>
                                <p class="text-muted mb-4">Будьте первым, кто создаст пост с этим тегом</p>
                                <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                                    <i class="fas fa-home me-2"></i>Вернуться на главную
                                </a>
                            </div>
                        @else
                            @if($tags->isEmpty())
                                <div class="text-center py-5">
                                    <img src="{{ asset('images/tag.svg') }}" class="mb-3" width="48" height="48" alt="Теги" style="filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);">
                                    <h5 class="fw-light">Пока нет тегов</h5>
                                    <p class="text-muted">Создайте первый тег, чтобы начать</p>
                                </div>
                            @else
                                <div class="row g-4">
                                    @foreach($tags as $tag)
                                        <div class="col-md-6">
                                            <a href="{{ route('tags.show', $tag) }}" class="text-decoration-none">
                                                <div class="card h-100 border-0 hover-card">
                                                    <div class="card-body p-4">
                                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                                            <h5 class="card-title mb-0 fw-light">
                                                                #{{ $tag->name }}
                                                            </h5>
                                                            <span class="badge bg-light text-dark rounded-pill px-3 py-1">
                                                                {{ $tag->posts_count }} {{ __('posts.posts.' . min($tag->posts_count, 20)) }}
                                                            </span>
                                                        </div>
                                                        @if($tag->description)
                                                            <p class="card-text text-muted small mb-3">{{ $tag->description }}</p>
                                                        @endif
                                                        <div class="d-flex align-items-center text-muted small">
                                                            <span class="me-3">Создан {{ $tag->created_at->diffForHumans() }}</span>
                                                            @if($tag->posts_count > 0)
                                                                <span>Последний пост {{ $tag->posts->first()->created_at->diffForHumans() }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    @else
                        @if($tags->isEmpty())
                            <div class="text-center py-5">
                                <img src="{{ asset('images/tag.svg') }}" class="mb-3" width="48" height="48" alt="Теги" style="filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);">
                                <h5 class="fw-light">Пока нет тегов</h5>
                                <p class="text-muted">Создайте первый тег, чтобы начать</p>
                            </div>
                        @else
                            <div class="row g-4">
                                @foreach($tags as $tag)
                                    <div class="col-md-6">
                                        <a href="{{ route('tags.show', $tag) }}" class="text-decoration-none">
                                            <div class="card h-100 border-0 hover-card">
                                                <div class="card-body p-4">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <h5 class="card-title mb-0 fw-light">
                                                            #{{ $tag->name }}
                                                        </h5>
                                                        <span class="badge bg-light text-dark rounded-pill px-3 py-1">
                                                            {{ $tag->posts_count }} {{ __('posts.posts.' . min($tag->posts_count, 20)) }}
                                                        </span>
                                                    </div>
                                                    @if($tag->description)
                                                        <p class="card-text text-muted small mb-3">{{ $tag->description }}</p>
                                                    @endif
                                                    <div class="d-flex align-items-center text-muted small">
                                                        <span class="me-3">Создан {{ $tag->created_at->diffForHumans() }}</span>
                                                        @if($tag->posts_count > 0)
                                                            <span>Последний пост {{ $tag->posts->first()->created_at->diffForHumans() }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Правая колонка -->
        <x-right-sidebar :popularTags="$popularTags" :topUsers="$topUsers" :isTagsPage="true" />
    </div>
</div>

@push('styles')
<!-- Стили для страницы тегов перенесены в общий файл CSS app.css -->
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('tagSearch');
    const tagCards = document.querySelectorAll('.col-md-6');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        tagCards.forEach(card => {
            const tagName = card.querySelector('.card-title').textContent.toLowerCase();
            
            if (tagName.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection 