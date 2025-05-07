@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
                <div class="card-header border-0">
                    <h3 class="card-title mb-0">Отладка бота</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Отправить сообщение боту</h5>
                                    <form action="{{ route('admin.bot-debug.test') }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="message" class="form-label">Сообщение:</label>
                                            <textarea 
                                                name="message" 
                                                id="message" 
                                                class="form-control @error('message') is-invalid @enderror" 
                                                rows="4" 
                                                placeholder="Введите сообщение для тестирования бота..."
                                                required
                                            >{{ old('message') }}</textarea>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-2"></i>Отправить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Статистика</h5>
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="text-center">
                                            <h6 class="text-muted mb-1">Всего сообщений</h6>
                                            <h4 class="mb-0">{{ count($responses) }}</h4>
                                        </div>
                                        <div class="text-center">
                                            <h6 class="text-muted mb-1">Последний ответ</h6>
                                            <h4 class="mb-0">{{ count($responses) > 0 ? $responses[0]['timestamp'] : 'Нет' }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header border-0">
                            <h5 class="card-title mb-0">История ответов</h5>
                        </div>
                        <div class="card-body">
                            @if(count($responses) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 15%">Время</th>
                                                <th style="width: 35%">Сообщение</th>
                                                <th style="width: 50%">Ответ бота</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($responses as $response)
                                                <tr>
                                                    <td class="text-muted">{{ $response['timestamp'] }}</td>
                                                    <td>{{ $response['message'] }}</td>
                                                    <td class="response-cell">{!! nl2br(e($response['response'])) !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Нет истории ответов. Отправьте сообщение боту, чтобы начать тестирование.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .response-cell {
        white-space: pre-wrap;
        word-wrap: break-word;
        max-width: 0;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-primary {
        padding: 0.5rem 1.5rem;
    }
    .alert {
        border-radius: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Автоматическая прокрутка к последнему ответу
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('.table');
        if (table) {
            table.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
</script>
@endpush 