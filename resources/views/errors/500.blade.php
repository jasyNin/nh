@extends('layouts.app')

@section('title', 'Ошибка сервера')

@section('content')
<div class="container" style="margin-top: 60px;">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 mb-4">500</h1>
            <h2 class="mb-4">Ошибка сервера</h2>
            <p class="text-muted mb-4">Произошла внутренняя ошибка сервера. Пожалуйста, попробуйте позже.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Вернуться на главную</a>
        </div>
    </div>
</div>
@endsection 