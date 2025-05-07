document.addEventListener('DOMContentLoaded', function() {
    const bookmarkButtons = document.querySelectorAll('.bookmark-button');
    
    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = this.closest('form');
            if (!form) return;
            
            // Добавляем класс active для анимации
            this.classList.add('active');
            
            // Отправляем форму
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Обновляем счетчик закладок в навигации
                const navBookmarksCount = document.querySelector('.nav-bookmarks-count');
                if (navBookmarksCount) {
                    const currentCount = parseInt(navBookmarksCount.textContent) || 0;
                    if (data.bookmarked) {
                        navBookmarksCount.textContent = currentCount + 1;
                        if (currentCount === 0) {
                            navBookmarksCount.style.display = 'flex';
                        }
                    } else {
                        const newCount = currentCount - 1;
                        if (newCount > 0) {
                            navBookmarksCount.textContent = newCount;
                        } else {
                            navBookmarksCount.style.display = 'none';
                        }
                    }
                }
                
                // Удаляем класс active после анимации
                setTimeout(() => {
                    this.classList.remove('active');
                    if (!data.bookmarked) {
                        this.classList.remove('active');
                    }
                }, 500);
            })
            .catch(error => {
                console.error('Error:', error);
                this.classList.remove('active');
            });
        });
    });
}); 