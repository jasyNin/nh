@extends('layouts.app')

@section('title', 'Просмотр жалобы')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-10">
            <div class="admin-dashboard">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Просмотр жалобы #{{ $complaint->id }}</h1>
                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Назад к списку
                    </a>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">Информация о жалобе</h5>
                                <table class="table">
                                    <tr>
                                        <th>ID:</th>
                                        <td>{{ $complaint->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Тип жалобы:</th>
                                        <td>{{ $complaint->type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Статус:</th>
                                        <td>
                                            @if($complaint->status === 'new')
                                                <span class="badge bg-primary">Новая</span>
                                            @elseif($complaint->status === 'open')
                                                <span class="badge bg-warning">Открыт спор</span>
                                            @elseif($complaint->status === 'unjustified')
                                                <span class="badge bg-danger">Не обоснована</span>
                                            @elseif($complaint->status === 'closed')
                                                <span class="badge bg-success">Закрыта</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Дата создания:</th>
                                        <td>{{ $complaint->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title">Информация о пользователе</h5>
                                <table class="table">
                                    <tr>
                                        <th>ID пользователя:</th>
                                        <td>{{ $complaint->user->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Имя пользователя:</th>
                                        <td>{{ $complaint->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $complaint->user->email }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5 class="card-title">Причина жалобы</h5>
                            <div class="p-3 bg-light rounded">
                                {{ $complaint->reason }}
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5 class="card-title">Содержание жалобы</h5>
                            <div class="p-3 bg-light rounded">
                                @php
                                    $type = str_replace('App\\Models\\', '', $complaint->complaintable_type);
                                @endphp
                                
                                @if($type === 'Post')
                                    <h6>Пост:</h6>
                                    @if(isset($complaint->complaintable) && isset($complaint->complaintable->content))
                                        <p>{{ $complaint->complaintable->content }}</p>
                                    @elseif(isset($complaint->complaintable) && isset($complaint->complaintable->title))
                                        <p>{{ $complaint->complaintable->title }}</p>
                                    @else
                                        <p>Содержимое поста недоступно</p>
                                    @endif
                                    
                                    @if(isset($complaint->complaintable) && method_exists($complaint->complaintable, 'getUrl'))
                                        <a href="{{ $complaint->complaintable->getUrl() }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                            Перейти к посту
                                        </a>
                                    @elseif(isset($complaint->complaintable))
                                        <a href="{{ route('posts.show', $complaint->complaintable) }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                            Перейти к посту
                                        </a>
                                    @else
                                        <div class="alert alert-warning mt-2">
                                            Пост не найден или был удален
                                        </div>
                                    @endif
                                @elseif($type === 'Comment')
                                    <h6>Комментарий:</h6>
                                    @if(isset($complaint->complaintable) && isset($complaint->complaintable->content))
                                        <p>{{ $complaint->complaintable->content }}</p>
                                    @else
                                        <p>Содержимое комментария недоступно</p>
                                    @endif
                                    
                                    @if(isset($complaint->complaintable) && method_exists($complaint->complaintable, 'getUrl'))
                                        <a href="{{ $complaint->complaintable->getUrl() }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                            Перейти к комментарию
                                        </a>
                                    @elseif(isset($complaint->complaintable) && isset($complaint->complaintable->post))
                                        <a href="{{ route('posts.show', $complaint->complaintable->post) }}#comment-{{ $complaint->complaintable->id }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                            Перейти к комментарию
                                        </a>
                                    @else
                                        <div class="alert alert-warning mt-2">
                                            Комментарий не найден или был удален
                                        </div>
                                    @endif
                                @elseif($type === 'CommentReply')
                                    <h6>Ответ на комментарий:</h6>
                                    @if(isset($complaint->complaintable) && isset($complaint->complaintable->content))
                                        <p>{{ $complaint->complaintable->content }}</p>
                                    @else
                                        <p>Содержимое ответа недоступно</p>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <h6>Комментарий, на который дан ответ:</h6>
                                        @if(isset($complaint->complaintable) && isset($complaint->complaintable->comment) && isset($complaint->complaintable->comment->content))
                                            <p>{{ $complaint->complaintable->comment->content }}</p>
                                        @else
                                            <p>Содержимое комментария недоступно</p>
                                        @endif
                                    </div>
                                    
                                    @if(isset($complaint->complaintable) && method_exists($complaint->complaintable, 'getUrl'))
                                        <a href="{{ $complaint->complaintable->getUrl() }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                            Перейти к ответу
                                        </a>
                                    @elseif(isset($complaint->complaintable) && isset($complaint->complaintable->comment) && isset($complaint->complaintable->comment->post))
                                        <a href="{{ route('posts.show', $complaint->complaintable->comment->post) }}#reply-{{ $complaint->complaintable->id }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                            Перейти к ответу
                                        </a>
                                    @else
                                        <div class="alert alert-warning mt-2">
                                            Ответ не найден или был удален
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning">
                                        Неизвестный тип объекта жалобы
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h5 class="card-title">Действия</h5>
                            <div class="d-flex">
                                <form action="{{ route('admin.complaints.updateStatus', $complaint) }}" method="POST" class="me-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="closed">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Вы уверены, что хотите закрыть эту жалобу?')">
                                        Закрыть жалобу
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.complaints.updateStatus', $complaint) }}" method="POST" class="me-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="unjustified">
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Вы уверены, что хотите отметить эту жалобу как необоснованную?')">
                                        Отметить как необоснованную
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.complaints.destroy', $complaint) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить эту жалобу?')">
                                        Удалить жалобу
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reportPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="reportPostModalLabel{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 0;">
            <div class="modal-header">
                <h5 class="modal-title" id="reportPostModalLabel{{ $post->id }}">Пожаловаться на пост</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('complaints.store') }}" method="POST">
                @csrf
                <input type="hidden" name="complaintable_id" value="{{ $post->id }}">
                <input type="hidden" name="complaintable_type" value="{{ get_class($post) }}">
                <input type="hidden" name="type" value="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Причина жалобы</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Опишите подробнее причину жалобы..." required minlength="10"></textarea>
                        <div class="form-text">Минимум 10 символов</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отправить жалобу</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 