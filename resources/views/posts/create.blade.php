@extends('layouts.app')

@section('title', 'Создать пост')

@section('content')
<style>
    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #E2E8F0;
        padding: 12px 16px;
        font-size: 14px;
        background-color: #F5F5F5;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #1682FD;
        box-shadow: 0 0 0 3px rgba(22, 130, 253, 0.1);
        background-color: #F5F5F5;
    }
    
    .btn-secondary {
        border-radius: 12px;
        background-color: #F3F4F6;
        color: #808080;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .btn-primary {
        border-radius: 8px;
        background-color: #1682FD;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .btn-outline-primary {
        border-radius: 12px;
        background-color: #EAEAEA;
        border: none;
        color: #808080;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .form-label {
        font-size: 17px;
        font-weight: 400;
        color: #272727;
        margin-bottom: 8px;
    }
    
    .tag-input-container {
        position: relative;
        margin-bottom: 24px;
    }
    
    .tag-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        z-index: 1000;
        display: none;
    }
    
    .tag-suggestion {
        padding: 8px 16px;
        cursor: pointer;
    }
    
    .tag-suggestion:hover {
        background-color: #F3F4F6;
    }
    
    .selected-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }
    
    .selected-tag {
        display: flex;
        align-items: center;
        background-color: #F3F4F6;
        border-radius: 16px;
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .tag-remove {
        margin-left: 6px;
        cursor: pointer;
        color: #9CA3AF;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 24px;
    }
    
    .form-container {
        background-color: #FFFFFF;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .input-section {
        margin-bottom: 24px;
    }
</style>
<div class="container-fluid" style="margin-top: 80px;">
    <div class="row">
        <!-- Боковое меню -->
        <x-side-menu />
        @include('components.side-menu-styles')

        <!-- Основной контент -->
        <div class="col-md-7">
            <h1 class="page-title">Создать пост</h1>
            
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="post-create-form">
                @csrf
                <input type="hidden" name="is_draft" id="is-draft" value="0">
                <input type="hidden" name="redirect_to" id="redirect-to" value="">

                <div class="form-container">
                    <!-- Заголовок -->
                    <div class="input-section">
                        <label for="title" class="form-label">Заголовок</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Введите заголовок" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Содержание -->
                    <div class="input-section">
                        <label for="content" class="form-label">Содержание</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10" placeholder="Напишите содержание..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Тип поста -->
                    <div class="input-section">
                        <label for="type" class="form-label">Тип поста</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="post" {{ old('type') === 'post' ? 'selected' : '' }}>Запись</option>
                            <option value="question" {{ old('type') === 'question' ? 'selected' : '' }}>Вопрос</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Теги -->
                    <div class="input-section tag-input-container">
                        <label for="tag-input" class="form-label">Теги</label>
                        <input type="text" class="form-control" id="tag-input" placeholder="Добавьте теги">
                        <input type="hidden" name="tags" id="tags" value="{{ old('tags') }}">
                        <div class="selected-tags"></div>
                        <div class="tag-suggestions"></div>
                        @error('tags')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Введите и нажмите Enter для добавления тега</div>
                    </div>

                    <!-- Изображение -->
                    <div class="input-section">
                        <label for="image" class="form-label">Изображение</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <div id="image-preview-container" class="mt-3" style="display: none;">
                            <img id="image-preview" src="#" alt="Предпросмотр" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                            <button type="button" class="btn btn-link text-danger p-0 mt-2" id="remove-image">Удалить изображение</button>
                        </div>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Кнопки действий -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('home') }}" class="btn btn-secondary">Отмена</a>
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2" id="save-draft">Сохранить как черновик</button>
                            <button type="submit" class="btn btn-primary">Опубликовать</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Правая колонка -->
        <x-right-sidebar :popularTags="$popularTags" />
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Обработка загрузки изображения
        const imageInput = document.getElementById('image');
        const previewContainer = document.getElementById('image-preview-container');
        const imagePreview = document.getElementById('image-preview');
        const removeImageBtn = document.getElementById('remove-image');
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            previewContainer.style.display = 'none';
        });
        
        // Сохранение как черновик
        const saveDraftBtn = document.getElementById('save-draft');
        const draftInput = document.getElementById('is-draft');
        const redirectInput = document.getElementById('redirect-to');
        const postForm = document.getElementById('post-create-form');
        
        saveDraftBtn.addEventListener('click', function() {
            draftInput.value = '1';
            redirectInput.value = '/drafts';
            postForm.submit();
        });
        
        // Система тегов
        const tagInput = document.getElementById('tag-input');
        const tagsHiddenInput = document.getElementById('tags');
        const selectedTagsContainer = document.querySelector('.selected-tags');
        const popularTags = {!! json_encode($popularTags->pluck('name')->toArray()) !!};
        let selectedTags = [];
        
        // Если есть сохраненные теги, загружаем их
        const savedTags = tagsHiddenInput.value;
        if (savedTags) {
            selectedTags = savedTags.split(',').map(tag => tag.trim());
            renderSelectedTags();
        }
        
        tagInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                
                const tagValue = this.value.trim();
                if (tagValue && !selectedTags.includes(tagValue)) {
                    selectedTags.push(tagValue);
                    tagsHiddenInput.value = selectedTags.join(',');
                    renderSelectedTags();
                }
                
                this.value = '';
            }
        });
        
        tagInput.addEventListener('input', function() {
            const value = this.value.trim().toLowerCase();
            const suggestionsContainer = document.querySelector('.tag-suggestions');
            
            if (value.length < 2) {
                suggestionsContainer.style.display = 'none';
                return;
            }
            
            const filteredTags = popularTags.filter(tag => 
                tag.toLowerCase().includes(value) && !selectedTags.includes(tag)
            );
            
            if (filteredTags.length > 0) {
                suggestionsContainer.innerHTML = '';
                filteredTags.forEach(tag => {
                    const suggestionEl = document.createElement('div');
                    suggestionEl.className = 'tag-suggestion';
                    suggestionEl.textContent = tag;
                    suggestionEl.addEventListener('click', function() {
                        selectedTags.push(tag);
                        tagsHiddenInput.value = selectedTags.join(',');
                        renderSelectedTags();
                        tagInput.value = '';
                        suggestionsContainer.style.display = 'none';
                    });
                    suggestionsContainer.appendChild(suggestionEl);
                });
                suggestionsContainer.style.display = 'block';
            } else {
                suggestionsContainer.style.display = 'none';
            }
        });
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.tag-input-container')) {
                document.querySelector('.tag-suggestions').style.display = 'none';
            }
        });
        
        function renderSelectedTags() {
            selectedTagsContainer.innerHTML = '';
            
            selectedTags.forEach((tag, index) => {
                const tagEl = document.createElement('div');
                tagEl.className = 'selected-tag';
                tagEl.innerHTML = `
                    ${tag}
                    <span class="tag-remove" data-index="${index}">×</span>
                `;
                selectedTagsContainer.appendChild(tagEl);
            });
            
            document.querySelectorAll('.tag-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    selectedTags.splice(index, 1);
                    tagsHiddenInput.value = selectedTags.join(',');
                    renderSelectedTags();
                });
            });
        }
    });
</script>
@endpush
@endsection 