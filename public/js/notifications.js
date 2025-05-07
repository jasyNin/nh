// Функция для обновления счетчика уведомлений
function updateNotificationCount() {
    fetch('/notifications/unviewed-count')
        .then(response => response.json())
        .then(data => {
            const indicator = document.getElementById('notificationIndicator');
            if (indicator) {
                if (data.count > 0) {
                    indicator.style.display = 'block';
                } else {
                    indicator.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error updating notification count:', error);
        });
}

// Функция для загрузки уведомлений
function loadNotifications() {
    const notificationsList = document.getElementById('notificationsList');
    if (!notificationsList) return;

    // Добавляем индикатор загрузки
    notificationsList.innerHTML = '<div class="dropdown-item text-center"><div class="spinner-border spinner-border-sm" role="status"></div></div>';

    fetch('/notifications/list')
        .then(response => response.json())
        .then(data => {
            notificationsList.innerHTML = '';

            if (data.notifications.length === 0) {
                const emptyState = document.createElement('div');
                emptyState.className = 'dropdown-item text-muted text-center py-3';
                emptyState.innerHTML = `
                    <svg class="mb-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <div>Нет новых уведомлений</div>
                `;
                notificationsList.appendChild(emptyState);
                return;
            }

            data.notifications.forEach(notification => {
                const notificationItem = document.createElement('div');
                notificationItem.className = 'dropdown-item notification-item' + (notification.is_read ? '' : ' unread');
                notificationItem.innerHTML = `
                    <div class="d-flex align-items-center p-2">
                        <div class="notification-avatar me-3">
                            ${notification.user_avatar || `
                                <div class="notification-avatar-placeholder">
                                    ${notification.user_name.charAt(0)}
                                </div>
                            `}
                        </div>
                        <div class="notification-content flex-grow-1">
                            <p class="mb-1">${notification.message}</p>
                            <small class="text-muted">${notification.created_at}</small>
                        </div>
                        ${!notification.is_read ? '<span class="notification-badge"></span>' : ''}
                    </div>
                `;
                
                // Добавляем анимацию при появлении
                notificationItem.style.opacity = '0';
                notificationItem.style.transform = 'translateY(10px)';
                
                notificationItem.addEventListener('click', () => markAsRead(notification.id));
                notificationsList.appendChild(notificationItem);
                
                // Запускаем анимацию появления
                setTimeout(() => {
                    notificationItem.style.transition = 'all 0.3s ease';
                    notificationItem.style.opacity = '1';
                    notificationItem.style.transform = 'translateY(0)';
                }, 50);
            });
        })
        .catch(error => {
            notificationsList.innerHTML = '<div class="dropdown-item text-danger">Ошибка загрузки уведомлений</div>';
            console.error('Error loading notifications:', error);
        });
}

// Функция для отметки уведомления как прочитанного
function markAsRead(notificationId) {
    const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
    if (notificationItem) {
        // Добавляем анимацию при отметке как прочитанное
        notificationItem.classList.add('marking-read');
    }

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
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        if (notificationItem) {
            notificationItem.classList.remove('marking-read');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Обновляем счетчик при загрузке страницы
    updateNotificationCount();

    // Обновляем счетчик каждые 30 секунд
    setInterval(updateNotificationCount, 30000);

    // Обработка клика по значку уведомлений
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    if (notificationsDropdown) {
        notificationsDropdown.addEventListener('show.bs.dropdown', function() {
            // Отмечаем все уведомления как просмотренные при открытии dropdown
            fetch('/notifications/viewed', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(() => {
                // Скрываем индикатор после просмотра
                const indicator = document.getElementById('notificationIndicator');
                if (indicator) {
                    indicator.style.display = 'none';
                }
            });
        });
    }
}); 