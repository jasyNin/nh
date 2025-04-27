document.addEventListener('DOMContentLoaded', function() {
    // Обработчики для кнопок комментариев
    document.querySelectorAll('.comment-toggle').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const postId = this.closest('.post-card').querySelector('.comments-section').id.split('-').pop();
            const commentsSection = document.getElementById(`comments-section-${postId}`);
            
            if (commentsSection) {
                commentsSection.classList.toggle('d-none');
                const commentIcon = this.querySelector('img');
                if (commentIcon) {
                    commentIcon.src = commentsSection.classList.contains('d-none') 
                        ? '/images/icons/comment.svg'
                        : '/images/icons/comment-active.svg';
                }
            }
        });
    });

    // Обработчики для кнопок ответов
    document.querySelectorAll('.toggle-replies-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const repliesContainer = this.nextElementSibling;
            
            if (repliesContainer) {
                repliesContainer.classList.toggle('d-none');
                this.textContent = repliesContainer.classList.contains('d-none')
                    ? 'Показать ответы'
                    : 'Скрыть ответы';
            }
        });
    });

    const commentLikeButtons = document.querySelectorAll('.comment-like-button');
    
    commentLikeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('COMMENT LIKE HANDLER', this);
            
            const commentId = this.dataset.commentId;
            if (!commentId || isNaN(Number(commentId)) || commentId === 'undefined' || commentId === '' || commentId === null) {
                console.error('Comment ID is invalid:', commentId);
                return;
            }
            
            const likeCount = this.querySelector('.like-count');
            const likeIcon = this.querySelector('.like-icon');
            
            fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }
                if (likeCount) likeCount.textContent = data.likes_count;
                if (likeIcon) {
                    if (data.liked) {
                        likeIcon.classList.add('text-danger');
                        likeIcon.classList.remove('text-muted');
                    } else {
                        likeIcon.classList.remove('text-danger');
                        likeIcon.classList.add('text-muted');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
}); 