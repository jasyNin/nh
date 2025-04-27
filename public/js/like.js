document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.post-card .like-button');
    
    likeButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            const likesCount = this.querySelector('.likes-count');
            const icon = this.querySelector('.like-icon');
            
            try {
                const response = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    // Мгновенно обновляем состояние кнопки
                    this.classList.toggle('active');
                    
                    // Мгновенно обновляем счетчик
                    likesCount.textContent = data.likes_count;
                    
                    // Добавляем анимацию для иконки
                    icon.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 200);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });
}); 