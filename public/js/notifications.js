// Функция для обновления счетчика уведомлений
function updateNotificationCount() {
    fetch('/notifications/count')
        .then(response => response.json())
        .then(data => {
            document.getElementById('notificationCount').textContent = data.count;
        });
}

// Функция для загрузки уведомлений
function loadNotifications() {
    fetch('/notifications/list')
        .then(response => response.json())
        .then(data => {
            const notificationsList = document.getElementById('notificationsList');
            notificationsList.innerHTML = '';

            if (data.notifications.length === 0) {
                notificationsList.innerHTML = '<div class="dropdown-item text-muted">Нет новых уведомлений</div>';
                return;
            }

            data.notifications.forEach(notification => {
                const notificationItem = document.createElement('div');
                notificationItem.className = 'dropdown-item notification-item' + (notification.is_read ? '' : ' unread');
                notificationItem.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="notification-content">
                            <p class="mb-0">${notification.message}</p>
                            <small class="text-muted">${notification.created_at}</small>
                        </div>
                    </div>
                `;
                notificationItem.addEventListener('click', () => markAsRead(notification.id));
                notificationsList.appendChild(notificationItem);
            });
        });
}

// Функция для отметки уведомления как прочитанного
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(() => {
        loadNotifications();
        updateNotificationCount();
    });
}

// Обновляем уведомления каждые 30 секунд
setInterval(() => {
    updateNotificationCount();
    if (document.querySelector('.notifications-dropdown.show')) {
        loadNotifications();
    }
}, 30000);

// Загружаем уведомления при открытии выпадающего списка
document.getElementById('notificationsDropdown').addEventListener('show.bs.dropdown', loadNotifications); 