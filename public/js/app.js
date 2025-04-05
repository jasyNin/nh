// Основной файл JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Инициализация Bootstrap компонентов
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Обработка форм с атрибутом data-remote="true"
    document.querySelectorAll('form[data-remote="true"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        // Ошибка валидации
                        return response.json().then(data => {
                            throw new Error(JSON.stringify(data));
                        });
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Закрываем модальное окно, если оно есть
                const modal = this.closest('.modal');
                if (modal) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
                
                // Очищаем форму
                this.reset();
                
                // Если есть HTML-ответ, добавляем его на страницу
                if (data.html) {
                    // Находим контейнер для комментариев или ответов
                    const container = document.querySelector('.comments-container, .replies-container');
                    if (container) {
                        // Добавляем новый комментарий или ответ в начало контейнера
                        container.insertAdjacentHTML('afterbegin', data.html);
                    }
                    
                    // Обновляем счетчик комментариев или ответов, если он есть
                    if (data.replies_count !== undefined) {
                        const counter = document.querySelector('.replies-count');
                        if (counter) {
                            counter.textContent = data.replies_count;
                        }
                    }
                }
                
                // Показываем сообщение об успехе
                if (data.message) {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                try {
                    // Пытаемся распарсить ошибку валидации
                    const validationErrors = JSON.parse(error.message);
                    let errorMessage = 'Ошибка валидации:\n';
                    
                    // Формируем сообщение об ошибке
                    for (const field in validationErrors.errors) {
                        errorMessage += `${validationErrors.errors[field].join('\n')}\n`;
                    }
                    
                    alert(errorMessage);
                } catch (e) {
                    // Если не удалось распарсить ошибку, показываем общее сообщение
                    alert('Произошла ошибка при отправке формы');
                }
            });
        });
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