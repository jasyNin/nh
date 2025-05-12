document.addEventListener('DOMContentLoaded', function() {
    // Обработка открытия/закрытия секции комментариев
    document.querySelectorAll('.comment-toggle').forEach(button => {
        button.addEventListener('click', function() {
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
    });

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

    // Инлайн-редактирование комментария
    document.querySelectorAll('.edit-comment-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const commentId = this.dataset.commentId;
            document.getElementById(`comment-content-${commentId}`).style.display = 'none';
            document.getElementById(`edit-comment-form-${commentId}`).style.display = 'block';
        });
    });
    document.querySelectorAll('.cancel-edit-comment').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            document.getElementById(`edit-comment-form-${commentId}`).style.display = 'none';
            document.getElementById(`comment-content-${commentId}`).style.display = 'block';
        });
    });
    document.querySelectorAll('.inline-edit-comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const commentId = this.dataset.commentId;
            const content = this.querySelector('textarea').value;
            fetch(`/comments/${commentId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById(`comment-content-${commentId}`).textContent = data.content || content;
                document.getElementById(`edit-comment-form-${commentId}`).style.display = 'none';
                document.getElementById(`comment-content-${commentId}`).style.display = 'block';
            });
        });
    });

    // Инлайн-редактирование ответа
    document.querySelectorAll('.edit-reply-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const replyId = this.dataset.replyId;
            document.getElementById(`reply-content-${replyId}`).style.display = 'none';
            document.getElementById(`edit-reply-form-${replyId}`).style.display = 'block';
        });
    });
    document.querySelectorAll('.cancel-edit-reply').forEach(btn => {
        btn.addEventListener('click', function() {
            const replyId = this.dataset.replyId;
            document.getElementById(`edit-reply-form-${replyId}`).style.display = 'none';
            document.getElementById(`reply-content-${replyId}`).style.display = 'block';
        });
    });
    document.querySelectorAll('.inline-edit-reply-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const replyId = this.dataset.replyId;
            const content = this.querySelector('textarea').value;
            fetch(`/replies/${replyId}`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById(`reply-content-${replyId}`).textContent = data.content || content;
                document.getElementById(`edit-reply-form-${replyId}`).style.display = 'none';
                document.getElementById(`reply-content-${replyId}`).style.display = 'block';
            });
        });
    });
}); 