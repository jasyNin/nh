document.addEventListener('DOMContentLoaded', function() {
    // Обработка лайков
    document.addEventListener('click', function(e) {
        const likeButton = e.target.closest('.like-button');
        if (!likeButton) return;

        e.preventDefault();
        
        const postId = likeButton.dataset.postId;
        const likeImg = likeButton.querySelector('img');
        const likesCount = likeButton.querySelector('.likes-count');
        
        fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Обновляем состояние кнопки
            if (data.liked) {
                likeImg.classList.add('liked');
                likeButton.classList.add('liked');
            } else {
                likeImg.classList.remove('liked');
                likeButton.classList.remove('liked');
            }
            
            // Анимация
            likeButton.classList.add('animate');
            setTimeout(() => {
                likeButton.classList.remove('animate');
            }, 300);
            
            // Обновляем счетчик
            likesCount.textContent = data.likes_count;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Обработка комментариев
    document.addEventListener('click', function(e) {
        const commentToggle = e.target.closest('.comment-toggle');
        if (!commentToggle) return;

        const postId = commentToggle.dataset.postId;
        const commentsSection = document.querySelector(`#comments-section-${postId}`);
        
        if (commentsSection) {
            commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
        }
    });

    // Обработка репостов
    document.addEventListener('click', function(e) {
        const repostButton = e.target.closest('.repost-button');
        if (!repostButton) return;

        e.preventDefault();
        
        const postId = repostButton.dataset.postId;
        
        fetch(`/posts/${postId}/repost`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const repostsCount = repostButton.querySelector('span');
            if (repostsCount) {
                repostsCount.textContent = data.reposts_count;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Обработка закладок
    document.addEventListener('click', function(e) {
        const bookmarkButton = e.target.closest('.bookmark-button');
        if (!bookmarkButton) return;

        e.preventDefault();
        
        const form = bookmarkButton.closest('form');
        const postId = bookmarkButton.dataset.postId;
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const bookmarkImg = bookmarkButton.querySelector('img');
            if (data.bookmarked) {
                bookmarkButton.classList.add('active');
                bookmarkImg.classList.add('bookmarked');
            } else {
                bookmarkButton.classList.remove('active');
                bookmarkImg.classList.remove('bookmarked');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
}); 