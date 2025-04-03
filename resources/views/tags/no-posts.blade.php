@extends('layouts.app')

@section('title', 'Постов с тегом нет')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <div class="card border-0 bg-transparent">
                <div class="card-body text-center py-5">
                    <img src="{{ asset('images/tag.svg') }}" class="mb-3" width="48" height="48" alt="Теги" style="filter: invert(31%) sepia(98%) saturate(1234%) hue-rotate(212deg) brightness(98%) contrast(101%);">
                    <h5 class="fw-light">Постов с тегом #{{ $tag->name }} пока нет</h5>
                    <p class="text-muted mb-4">Будьте первым, кто создаст пост с этим тегом</p>
                    <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 rounded-pill">
                        <i class="fas fa-home me-2"></i>Вернуться на главную
                    </a>
                </div>
            </div>
        </div>

        <!-- Правая колонка -->
        <x-right-sidebar :popularTags="$popularTags" :topUsers="$topUsers" :isTagsPage="true" />
    </div>
</div>

@push('styles')
<style>
.btn-primary {
    background-color: #1682FD;
    border-color: #1682FD;
}

.btn-primary:hover {
    background-color: #1470e0;
    border-color: #1470e0;
}
</style>
@endpush
@endsection 