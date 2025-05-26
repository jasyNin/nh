document.addEventListener('DOMContentLoaded', function() {
    const shareButtons = document.querySelectorAll('.share-button');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.dataset.postId;
            const postUrl = this.dataset.postUrl;
            
            if (!postId) {
                console.error('Post ID not found');
                return;
            }
            
            try {
                // Пытаемся скопировать URL в буфер обмена
                try {
                    await navigator.clipboard.writeText(postUrl);
                } catch (clipboardError) {
                    console.log('Копирование в буфер обмена не поддерживается');
                }
                
                // Отправляем запрос на сервер для обновления счетчика репостов
                const response = await fetch(`/posts/${postId}/repost`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                
                // Обновляем счетчик репостов
                const repostCount = this.querySelector('span');
                if (repostCount) {
                    repostCount.textContent = data.reposts_count;
                }
                
                // Меняем цвет иконки и счетчика
                const icon = this.querySelector('img');
                if (icon) {
                    if (data.is_reposted) {
                        icon.style.filter = 'brightness(0) saturate(100%) invert(35%) sepia(98%) saturate(1352%) hue-rotate(202deg) brightness(97%) contrast(101%)';
                        if (repostCount) {
                            repostCount.style.color = '#1682FD';
                        }
                    } else {
                        icon.style.filter = '';
                        if (repostCount) {
                            repostCount.style.color = '';
                        }
                    }
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