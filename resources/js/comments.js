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
}); 