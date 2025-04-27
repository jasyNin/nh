<div class="mb-3">
    <label for="content" class="form-label">Содержание</label>
    <textarea class="form-control" id="content" name="content" rows="5" required>{{ old('content', $post->content ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="image" class="form-label">Изображение</label>
    <input type="file" class="form-control" id="image" name="image" accept="image/*">
    @if(isset($post) && $post->image)
        <div class="mt-2">
            <img src="{{ Storage::url($post->image) }}" alt="Текущее изображение" class="img-thumbnail" style="max-height: 200px">
        </div>
    @endif
</div>

<div class="mb-3">
    <label for="type" class="form-label">Тип</label>
    <select class="form-select" id="type" name="type" required>
        <option value="" disabled selected>Выберите тип</option>
        <option value="news" {{ old('type', $post->type) === 'news' ? 'selected' : '' }}>Новость</option>
        <option value="article" {{ old('type', $post->type) === 'article' ? 'selected' : '' }}>Статья</option>
        <option value="review" {{ old('type', $post->type) === 'review' ? 'selected' : '' }}>Обзор</option>
    </select>
</div> 