document.addEventListener('DOMContentLoaded', function() {
    const shareButtons = document.querySelectorAll('.share-button');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const postUrl = this.dataset.postUrl;
            const postId = this.closest('.post-card').querySelector('.like-button').dataset.postId;
            
            try {
                // Копируем URL в буфер обмена
                await navigator.clipboard.writeText(postUrl);
                
                // Отправляем запрос на сервер для обновления счетчика репостов
                const response = await fetch(`/posts/${postId}/repost`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                // Обновляем счетчик репостов
                const repostCount = this.querySelector('span');
                repostCount.textContent = data.reposts_count;
                
                // Меняем цвет иконки и счетчика
                const icon = this.querySelector('img');
                if (data.is_reposted) {
                    icon.style.filter = 'brightness(0) saturate(100%) invert(35%) sepia(98%) saturate(1352%) hue-rotate(202deg) brightness(97%) contrast(101%)';
                    repostCount.style.color = '#1682FD';
                } else {
                    icon.style.filter = '';
                    repostCount.style.color = '';
                }
                
                // Показываем уведомление об успешном копировании
                const notification = document.createElement('div');
                notification.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                notification.style.zIndex = '9999';
                notification.textContent = 'Ссылка скопирована в буфер обмена';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 2000);
                
            } catch (error) {
                console.error('Ошибка при обработке репоста:', error);
            }
        });
    });
}); 