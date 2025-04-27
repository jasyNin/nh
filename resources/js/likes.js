document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.like-button');
    
    likeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const postId = this.dataset.postId;
            if (!postId) {
                console.error('Post ID is undefined');
                return;
            }
            
            const likeCount = this.querySelector('.like-count');
            const likeIcon = this.querySelector('.like-icon');
            
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
                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }
                
                likeCount.textContent = data.likes_count;
                
                if (data.liked) {
                    likeIcon.classList.add('text-danger');
                    likeIcon.classList.remove('text-muted');
                } else {
                    likeIcon.classList.remove('text-danger');
                    likeIcon.classList.add('text-muted');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
}); 