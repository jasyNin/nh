import './bootstrap';
import './comments';
import './likes';
import './complaints';

// Импортируем остальные скрипты для домашней страницы
import './home';

// Универсальный обработчик лайков постов
window.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', async function (e) {
        const btn = e.target.closest('.like-button[data-post-id]');
        if (!btn) return;
        console.log('POST LIKE HANDLER', btn);
        e.preventDefault();
        e.stopPropagation();
        if (!btn.dataset.postId) return;
        const postId = btn.dataset.postId;
        btn.disabled = true;
        try {
            const response = await fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
            if (!response.ok) throw new Error('Ошибка запроса');
            const data = await response.json();
            // Обновляем все кнопки и счетчики для этого поста
            document.querySelectorAll('.like-button[data-post-id="' + postId + '"]').forEach(function(button) {
                if (data.liked) {
                    button.classList.add('liked');
                    const icon = button.querySelector('img');
                    if (icon) icon.classList.add('liked');
                } else {
                    button.classList.remove('liked');
                    const icon = button.querySelector('img');
                    if (icon) icon.classList.remove('liked');
                }
            });
            document.querySelectorAll('.likes-count').forEach(function(span) {
                const parentBtn = span.closest('.like-button[data-post-id]');
                if (parentBtn && parentBtn.dataset.postId == postId) {
                    span.textContent = data.likes_count;
                }
            });
            // Перезагружаем страницу после успешного лайка
            console.log('RELOAD PAGE');
            window.location.reload();
        } catch (err) {
            alert('Ошибка! Попробуйте позже.');
        } finally {
            btn.disabled = false;
        }
    });
});
