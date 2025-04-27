document.addEventListener('DOMContentLoaded', function() {
    // Функция для показа уведомлений
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-message ${type} show`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Обработка лайков
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            if (!postId) {
                console.error('Post ID is missing');
                showToast('Ошибка: ID поста не найден', 'error');
                return;
            }

            const likesCount = this.querySelector('.likes-count');
            const likeIcon = this.querySelector('img');
            
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
                    // Обновляем счетчик лайков
                    likesCount.textContent = data.likes_count;
                    
                    // Обновляем стили и иконку
                    if (data.liked) {
                        this.classList.add('active');
                        likesCount.classList.add('text-danger');
                        likeIcon.src = '/images/icons/heart-filled.svg';
                    } else {
                        this.classList.remove('active');
                        likesCount.classList.remove('text-danger');
                        likeIcon.src = '/images/icons/heart.svg';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка при обработке лайка', 'error');
            });
        });
    });

    // Обработка комментариев
    document.querySelectorAll('.comment-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const commentsSection = document.getElementById(`comments-section-${postId}`);
            const commentIcon = this.querySelector('img');
            
            if (commentsSection) {
                const isHidden = commentsSection.classList.contains('d-none');
                
                if (isHidden) {
                    commentsSection.classList.remove('d-none');
                    commentIcon.src = '/images/icons/comment-filled.svg';
                } else {
                    commentsSection.classList.add('d-none');
                    commentIcon.src = '/images/icons/comment.svg';
                }
            }
        });
    });

    // Обработка кнопки "Поделиться"
    document.querySelectorAll('.share-button').forEach(button => {
        button.addEventListener('click', function() {
            const postUrl = this.dataset.postUrl;
            
            // Создаем временный input для копирования
            const tempInput = document.createElement('input');
            tempInput.value = postUrl;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            // Показываем уведомление
            showToast('Ссылка скопирована в буфер обмена');
            
            // Анимация кнопки
            this.classList.add('active');
            setTimeout(() => {
                this.classList.remove('active');
            }, 300);
        });
    });

    // Обработка формы комментариев
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const likeButton = this.closest('.post-card').querySelector('.like-button');
            if (!likeButton) {
                console.error('Like button not found');
                showToast('Ошибка: кнопка лайка не найдена', 'error');
                return;
            }
            
            const postId = likeButton.dataset.postId;
            if (!postId) {
                console.error('Post ID is missing');
                showToast('Ошибка: ID поста не найден', 'error');
                return;
            }
            
            const commentsList = this.closest('.comments-section').querySelector('.comments-list');
            const commentsCount = this.closest('.post-card').querySelector('.comments-count');
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Очищаем форму
                    this.reset();
                    
                    // Обновляем счетчик комментариев
                    commentsCount.textContent = data.comments_count;
                    
                    // Добавляем новый комментарий в начало списка
                    const newComment = document.createElement('div');
                    newComment.innerHTML = data.comment_html;
                    commentsList.insertBefore(newComment.firstElementChild, commentsList.firstChild);
                    
                    showToast('Комментарий добавлен');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка при добавлении комментария', 'error');
            });
        });
    });

    // Обработка загрузки дополнительных комментариев
    document.querySelectorAll('.load-more-comments').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const commentsList = this.closest('.comments-section').querySelector('.comments-list');
            const currentCount = commentsList.children.length;
            
            fetch(`/posts/${postId}/comments?skip=${currentCount}&take=3`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Добавляем новые комментарии
                        data.comments.forEach(commentHtml => {
                            const newComment = document.createElement('div');
                            newComment.innerHTML = commentHtml;
                            commentsList.appendChild(newComment.firstElementChild);
                        });
                        
                        // Обновляем или скрываем кнопку "Показать ещё"
                        if (data.has_more) {
                            this.textContent = `Показать ещё ${data.remaining_count} ${data.remaining_text}`;
                        } else {
                            this.style.display = 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Произошла ошибка при загрузке комментариев', 'error');
                });
        });
    });

    // Обработка репостов
    document.querySelectorAll('.repost-button').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            
            fetch(`/posts/${postId}/repost`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message);
                this.classList.add('active');
                setTimeout(() => {
                    this.classList.remove('active');
                }, 300);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка при репосте', 'error');
            });
        });
    });

    // Обработка закладок
    document.querySelectorAll('.bookmark-button').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            
            fetch(`/posts/${postId}/bookmark`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const img = this.querySelector('img');
                if (data.bookmarked) {
                    this.classList.add('active');
                    img.classList.add('bookmarked');
                    showToast('Пост добавлен в закладки');
                } else {
                    this.classList.remove('active');
                    img.classList.remove('bookmarked');
                    showToast('Пост удален из закладок');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка при обработке закладки', 'error');
            });
        });
    });
    
    // Обработка форм жалоб
    document.querySelectorAll('.complaint-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const complaintableId = this.dataset.complaintableId;
            const complaintableType = this.dataset.complaintableType;
            const type = this.querySelector('select[name="type"]').value;
            const reason = this.querySelector('textarea[name="reason"]').value;
            
            if (!type || !reason) {
                showToast('Пожалуйста, заполните все поля', 'error');
                return;
            }
            
            fetch('/complaints', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    complaintable_id: complaintableId,
                    complaintable_type: complaintableType,
                    type: type,
                    reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                showToast(data.message);
                
                // Закрываем модальное окно
                const modal = this.closest('.modal');
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
                
                // Очищаем форму
                this.reset();
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка при отправке жалобы', 'error');
            });
        });
    });

    // Обработка ответов на комментарии
    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            
            if (replyForm) {
                if (replyForm.style.display === 'none' || !replyForm.style.display) {
                    replyForm.style.display = 'block';
                    setTimeout(() => {
                        replyForm.classList.add('show');
                    }, 10);
                } else {
                    replyForm.classList.remove('show');
                    setTimeout(() => {
                        replyForm.style.display = 'none';
                    }, 300);
                }
            }
        });
    });
    
    // Обработка форм ответов на комментарии
    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const commentId = this.closest('.comment').id.replace('comment-', '');
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Очищаем форму
                    this.reset();
                    
                    // Обновляем счетчик ответов
                    const repliesCount = document.querySelector(`.replies-toggle[data-comment-id="${commentId}"]`);
                    if (repliesCount) {
                        repliesCount.textContent = `${data.replies_count} ${pluralize(data.replies_count, 'ответ', 'ответа', 'ответов')}`;
                    }
                    
                    // Добавляем новый ответ
                    const repliesContainer = document.getElementById(`replies-${commentId}`);
                    if (repliesContainer) {
                        const newReply = document.createElement('div');
                        newReply.className = 'reply';
                        newReply.id = `reply-${data.reply_id}`;
                        newReply.innerHTML = `
                            <div class="d-flex">
                                <a href="${data.user_url}" class="text-decoration-none me-2">
                                    <img src="${data.user_avatar}" alt="${data.user_name}" class="rounded-circle" width="32" height="32">
                                </a>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <a href="${data.user_url}" class="text-decoration-none text-dark fw-bold me-2">${data.user_name}</a>
                                        <small class="text-muted">только что</small>
                                    </div>
                                    <div class="reply-content">${data.content}</div>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="d-flex align-items-center me-3 like-button" data-reply-id="${data.reply_id}">
                                            <img src="/images/like.svg" alt="Лайк" width="16" height="14" class="me-1">
                                            <span class="likes-count">0</span>
                                        </div>
                                        <button class="btn btn-link text-dark p-0 ms-2" data-bs-toggle="modal" data-bs-target="#reportReplyModal${data.reply_id}">
                                            Пожаловаться
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        repliesContainer.insertBefore(newReply, repliesContainer.firstChild);
                        
                        // Добавляем обработчики событий для нового ответа
                        const newLikeButton = newReply.querySelector('.like-button');
                        if (newLikeButton) {
                            newLikeButton.addEventListener('click', function() {
                                const replyId = this.dataset.replyId;
                                
                                fetch(`/replies/${replyId}/like`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const likesCount = this.querySelector('.likes-count');
                                    if (likesCount) {
                                        likesCount.textContent = data.likes_count;
                                        if (data.liked) {
                                            likesCount.classList.add('liked');
                                            this.querySelector('img').classList.add('liked');
                                        } else {
                                            likesCount.classList.remove('liked');
                                            this.querySelector('img').classList.remove('liked');
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('Произошла ошибка при обработке лайка', 'error');
                                });
                            });
                        }
                    }
                    
                    showToast('Ответ успешно добавлен');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Произошла ошибка при добавлении ответа', 'error');
            });
        });
    });

    // Инициализация тултипов Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Инициализация поповеров Bootstrap
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}); 