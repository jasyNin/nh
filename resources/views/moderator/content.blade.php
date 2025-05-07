@extends('layouts.app')

@section('content')
<div class="row">
    <div class="container" style="margin-top: 80px;">
        <div class="row">
            <!-- Боковое меню -->
            <x-side-menu />
            <x-side-menu-styles />
            
            <!-- Основной контент -->
            <div class="col-md-9">
                <h1 class="mb-4">Контент</h1>
                
                <div class="card dashboard-card mb-4">
                    <div class="card-body">
                        <form action="{{ route('moderator.content') }}" method="GET" class="d-flex align-items-center">
                            <div class="search-box flex-grow-1 me-3">
                                <input type="text" name="search" class="form-control" placeholder="Поиск контента..." value="{{ request('search') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Поиск</button>
                        </form>
                    </div>
                </div>

                <ul class="nav nav-pills mb-4" id="contentTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="posts-tab" data-bs-toggle="pill" href="#posts" role="tab">Посты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="comments-tab" data-bs-toggle="pill" href="#comments" role="tab">Комментарии</a>
                    </li>
                </ul>

                <div class="tab-content" id="contentTabsContent">
                    <!-- Посты -->
                    <div class="tab-pane fade show active" id="posts" role="tabpanel">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Автор</th>
                                                <th>Заголовок</th>
                                                <th>Дата</th>
                                                <th>Статус</th>
                                                <th>Действия</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($posts as $post)
                                                <tr>
                                                    <td>{{ $post->id }}</td>
                                                    <td>{{ $post->user->name }}</td>
                                                    <td>{{ Str::limit($post->title, 50) }}</td>
                                                    <td>{{ $post->created_at->format('d.m.Y H:i') }}</td>
                                                    <td>
                                                        @if($post->is_hidden)
                                                            <span class="badge bg-danger">Скрыт</span>
                                                        @else
                                                            <span class="badge bg-success">Видим</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-info">Просмотр</a>
                                                        
                                                        @if(!$post->is_hidden)
                                                            <form action="{{ route('moderator.posts.hide', $post) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-warning">Скрыть</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Комментарии -->
                    <div class="tab-pane fade" id="comments" role="tabpanel">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Автор</th>
                                                <th>Комментарий</th>
                                                <th>Пост</th>
                                                <th>Дата</th>
                                                <th>Статус</th>
                                                <th>Действия</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($comments as $comment)
                                                <tr>
                                                    <td>{{ $comment->id }}</td>
                                                    <td>{{ $comment->user->name }}</td>
                                                    <td>{{ Str::limit($comment->content, 50) }}</td>
                                                    <td>{{ Str::limit($comment->post->title, 30) }}</td>
                                                    <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                                                    <td>
                                                        @if($comment->is_hidden)
                                                            <span class="badge bg-danger">Скрыт</span>
                                                        @else
                                                            <span class="badge bg-success">Видим</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('posts.show', $comment->post) }}" class="btn btn-sm btn-info">К посту</a>
                                                        
                                                        @if(!$comment->is_hidden)
                                                            <form action="{{ route('moderator.comments.hide', $comment) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-warning">Скрыть</button>
                                                            </form>
                                                        @endif
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
    </div>
</div>

@push('styles')
<style>
    .container {
        max-width: 1300px;
    }
    
    .dashboard-card {
        border: none;
        border-radius: 15px;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box input {
        padding-left: 2.5rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #2d3748;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .badge {
        padding: 0.5em 0.75em;
        font-weight: 500;
    }
    
    .btn-primary {
        background: #1682FD;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
    
    .nav-pills .nav-link {
        color: #495057;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        margin-right: 0.5rem;
    }
    
    .nav-pills .nav-link.active {
        background: #1682FD;
        color: white;
    }
</style>
@endpush
@endsection 