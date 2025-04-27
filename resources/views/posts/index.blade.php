<div class="card mb-4">
    <div class="card-body">
        <h2 class="card-title">{{ $post->title }}</h2>
        
        @if($post->image)
            <div class="mb-3">
                <img src="{{ url('storage/' . $post->image) }}" alt="Изображение поста" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
            </div>
        @endif
        
        <p class="card-text">{{ Str::limit($post->content, 200) }}</p>
    </div>
</div> 