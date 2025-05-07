// Обработчик лайков для комментариев и ответов
document.addEventListener('DOMContentLoaded', function() {
    // Обработчик для лайков комментариев
    document.querySelectorAll('.comment-like-button').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const commentId = this.dataset.commentId;
            const postId = this.dataset.postId;
            if (!commentId || !postId) return;
            
            try {
                const response = await fetch(`/comments/${commentId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ post_id: postId })
                });
                
                if (!response.ok) throw new Error('Ошибка запроса');
                
                const data = await response.json();
                
                // Обновляем все кнопки лайков для этого комментария
                document.querySelectorAll(`.comment-like-button[data-comment-id="${commentId}"]`).forEach(btn => {
                    const wrapper = btn.querySelector('.like-wrapper');
                    const icon = wrapper.querySelector('.like-icon');
                    const count = wrapper.querySelector('.like-count');
                    
                    if (data.liked) {
                        icon.classList.add('liked');
                        count.classList.add('liked');
                    } else {
                        icon.classList.remove('liked');
                        count.classList.remove('liked');
                    }
                    
                    count.textContent = data.likes_count;
                });
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    // Обработчик для лайков ответов
    document.querySelectorAll('.reply-like-button').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const replyId = this.dataset.replyId;
            if (!replyId) return;
            
            try {
                const response = await fetch(`/replies/${replyId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) throw new Error('Ошибка запроса');
                
                const data = await response.json();
                
                // Обновляем все кнопки лайков для этого ответа
                document.querySelectorAll(`.reply-like-button[data-reply-id="${replyId}"]`).forEach(btn => {
                    const icon = btn.querySelector('.like-icon');
                    const count = btn.querySelector('.like-count');
                    
                    if (icon) {
                        if (data.liked) {
                            icon.classList.remove('bi-heart');
                            icon.classList.add('bi-heart-fill', 'text-danger');
                        } else {
                            icon.classList.remove('bi-heart-fill', 'text-danger');
                            icon.classList.add('bi-heart');
                        }
                    }
                    
                    if (count) {
                        count.textContent = data.likes_count;
                    }
                });
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
}); 