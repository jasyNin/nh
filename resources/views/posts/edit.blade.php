@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Редактирование поста</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('posts.update', $post) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Заголовок</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Содержание</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10" required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Тип</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="post" {{ old('type', $post->type) === 'post' ? 'selected' : '' }}>Запись</option>
                                <option value="question" {{ old('type', $post->type) === 'question' ? 'selected' : '' }}>Вопрос</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Теги</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags', $post->tags->pluck('name')->join(', ')) }}" placeholder="Введите теги через запятую">
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Например: php, laravel, javascript</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">Отмена</a>
                            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection