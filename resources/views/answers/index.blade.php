@extends('layouts.app')

@section('title', 'Ответы')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ответы</h5>
                </div>
                <div class="card-body">
                    @if($answers->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/ansvers.svg') }}" class="mb-3" width="48" height="48" alt="Ответы" style="filter: brightness(0);">
                            <h5>Пока нет ответов</h5>
                            <p class="text-muted">Ответьте на вопрос, чтобы он появился здесь</p>
                        </div>
                    @else
                        @foreach($answers as $answer)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('posts.show', $answer->post) }}" class="text-decoration-none">
                                                    {{ $answer->post->title }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                {{ $answer->post->user->name }} • {{ $answer->post->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block">{{ $answer->created_at->diffForHumans() }}</small>
                                            <small class="text-muted">{{ $answer->comments_count }} {{ __('comments.comments.' . min($answer->comments_count, 20)) }}</small>
                                        </div>
                                    </div>
                                    <div class="answer-content">
                                        {!! $answer->content !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-4">
                            {{ $answers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 