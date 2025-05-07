@extends('layouts.app')

@section('title', 'Мои закладки')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            @if($bookmarks->isEmpty())
                <div class="card border-0">
                    <div class="card-body">
                        <div class="text-center py-5">
                            <img src="{{ asset('images/bookmark.svg') }}" class="mb-3" width="48" height="48" alt="Закладки">
                            <h5>У вас пока нет закладок</h5>
                            <p class="text-muted">Сохраняйте интересные посты в закладки</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 bg-transparent">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Закладки</h4>
                        <div class="search-container position-relative" style="width: 300px;">
                            <input type="text" id="bookmarkSearch" class="form-control" placeholder="Поиск по закладкам..." style="border-radius: 20px; padding: 8px 40px 8px 16px; font-size: 0.9rem; border: 1px solid #e0e0e0; background-color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); filter: invert(32%) sepia(98%) saturate(1234%) hue-rotate(210deg) brightness(97%) contrast(101%);">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="posts-container">
                            @foreach($bookmarks as $bookmark)
                                <div class="post-with-comments">
                                    <x-post-card :post="$bookmark->post" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .posts-container {
        margin-top: 1rem;
    }
    .post-with-comments {
        margin-bottom: 24px;
    }
    .post-with-comments .post-card {
        margin-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('bookmarkSearch');
    const postCards = document.querySelectorAll('.post-with-comments');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        postCards.forEach(card => {
            const postTitle = card.querySelector('h2.h5').textContent.toLowerCase();
            
            if (postTitle.includes(searchTerm)) {
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