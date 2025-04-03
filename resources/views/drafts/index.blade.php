@extends('layouts.app')

@section('title', 'Мои черновики')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Мои черновики</h5>
                </div>
                <div class="card-body">
                    @if($drafts->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/pen.svg') }}" class="mb-3" width="48" height="48" alt="Черновики">
                            <h5>У вас пока нет черновиков</h5>
                            <p class="text-muted">Создайте новый пост, чтобы начать писать</p>
                        </div>
                    @else
                        <div class="list-group">
                            @foreach($drafts as $draft)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $draft->title }}</h6>
                                            <small class="text-muted">Последнее обновление: {{ $draft->updated_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('posts.edit', $draft) }}" class="btn btn-sm btn-outline-primary">
                                                <img src="{{ asset('images/pen.svg') }}" class="me-1" width="16" height="16" alt="Редактировать">
                                                Редактировать
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $drafts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 