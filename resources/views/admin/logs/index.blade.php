@extends('layouts.app')
@section('title', 'Логи системы')
@section('content')
<div class="container main-content-container">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        <!-- Основной контент -->
        <div class="col-md-10" style="margin-top: 30px;">
            <h2>Последние логи</h2>
            <pre style="background:#222;color:#eee;padding:1em;border-radius:8px;max-height:600px;overflow:auto;">
@foreach(array_slice($logs,0,100) as $line)
{{ $line }}
@endforeach
            </pre>
        </div>
    </div>
</div>
@endsection 