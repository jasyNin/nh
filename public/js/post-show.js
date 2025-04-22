document.addEventListener('DOMContentLoaded', function() {
    // Обработка открытия/закрытия секции комментариев
    const commentToggle = document.querySelector('.comment-toggle');
    if (commentToggle) {
        commentToggle.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const commentsSection = document.querySelector(`#comments-section-${postId}`);
            
            if (commentsSection) {
                if (commentsSection.style.display === 'none') {
                    commentsSection.style.display = 'block';
                    this.classList.add('active');
                } else {
                    commentsSection.style.display = 'none';
                    this.classList.remove('active');
                }
            }
        });
    }

    // Обработка кнопки "Ответить"
    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const replyForm = document.querySelector(`#reply-form-${commentId}`);
            const userName = this.closest('.comment, .reply').querySelector('.text-dark.fw-bold').textContent;
            
            if (replyForm) {
                const textarea = replyForm.querySelector('textarea');
                if (replyForm.style.display === 'none') {
                    replyForm.style.display = 'block';
                    textarea.value = `@${userName} `;
                    textarea.focus();
                } else {
                    replyForm.style.display = 'none';
                    textarea.value = '';
                }
            }
        });
    });

    // Обработка кнопки "Отмена" в форме ответа
    document.querySelectorAll('.cancel-reply').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const replyForm = document.querySelector(`#reply-form-${commentId}`);
            
            if (replyForm) {
                replyForm.style.display = 'none';
                replyForm.querySelector('textarea').value = '';
            }
        });
    });

    // Обработка переключения отображения ответов
    document.querySelectorAll('.toggle-replies').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const repliesList = document.querySelector(`#replies-${commentId}`);
            const icon = this.querySelector('i');
            
            if (repliesList) {
                if (repliesList.style.display === 'none') {
                    repliesList.style.display = 'block';
                    icon.classList.remove('bi-chevron-down');
                    icon.classList.add('bi-chevron-up');
                } else {
                    repliesList.style.display = 'none';
                    icon.classList.remove('bi-chevron-up');
                    icon.classList.add('bi-chevron-down');
                }
            }
        });
    });

    // Обработка лайков комментариев и ответов
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const icon = this.querySelector('i');
            const countSpan = this.querySelector('span');
            
            fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.liked) {
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill', 'text-danger');
                    this.classList.add('active');
                } else {
                    icon.classList.remove('bi-heart-fill', 'text-danger');
                    icon.classList.add('bi-heart');
                    this.classList.remove('active');
                }
                countSpan.textContent = data.likes_count;
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Обработчик для показа/скрытия ответов при клике на счетчик
    document.querySelectorAll('.replies-count').forEach(counter => {
        counter.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const repliesList = document.querySelector(`#replies-${commentId}`);
            
            if (repliesList) {
                if (repliesList.style.display === 'none') {
                    repliesList.style.display = 'block';
                } else {
                    repliesList.style.display = 'none';
                }
            }
        });
    });

    // Обновление счетчика комментариев
    function updateCommentsCount(postId) {
        const countElement = document.querySelector(`.comment-toggle[data-post-id="${postId}"] span`);
        const commentsSection = document.querySelector(`#comments-section-${postId}`);
        const commentsCount = commentsSection.querySelectorAll('.comment').length;
        
        if (countElement) {
            countElement.textContent = commentsCount;
            commentsSection.querySelector('h6').textContent = `${commentsCount} комментариев`;
        }
    }

    // Обработка отправки комментария
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = this.closest('.comments-section').id.split('-')[2];
            const formData = new FormData(this);

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
                    // Добавление нового комментария в список
                    const commentsList = this.closest('.comments-section').querySelector('.comments-list');
                    commentsList.insertAdjacentHTML('afterbegin', data.html);
                    this.reset();
                    updateCommentsCount(postId);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
}); 