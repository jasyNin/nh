// Основной файл JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация Bootstrap компонентов
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Обработка лайков
    document.querySelectorAll('.like-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            if (postId) {
                fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const likesCount = this.querySelector('.likes-count');
                        if (likesCount) {
                            likesCount.textContent = data.likes_count;
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

    // Обработка закладок
    document.querySelectorAll('.bookmark-button').forEach(function(button) {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            if (postId) {
                fetch(`/posts/${postId}/bookmark`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.classList.toggle('active');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
}); 