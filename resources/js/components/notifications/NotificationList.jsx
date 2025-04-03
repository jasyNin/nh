import React from 'react';
import { Link } from 'react-router-dom';
import NotificationItem from './NotificationItem';

const NotificationList = ({ notifications, isLoading, onMarkAsRead, onMarkAllAsRead }) => {
    if (isLoading) {
        return <div className="loading">Загрузка...</div>;
    }

    if (!notifications.length) {
        return <div className="no-notifications">У вас пока нет уведомлений</div>;
    }

    const unreadCount = notifications.filter(n => !n.read).length;

    return (
        <div className="notification-list">
            <div className="notification-list__header">
                <h2>Уведомления</h2>
                {unreadCount > 0 && (
                    <button 
                        className="btn btn-secondary"
                        onClick={onMarkAllAsRead}
                    >
                        Отметить все как прочитанные
                    </button>
                )}
            </div>
            {notifications.map(notification => (
                <NotificationItem 
                    key={notification.id} 
                    notification={notification}
                    onMarkAsRead={onMarkAsRead}
                />
            ))}
        </div>
    );
};

export default NotificationList; 