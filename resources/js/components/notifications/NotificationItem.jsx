import React from 'react';
import { Link } from 'react-router-dom';

const NotificationItem = ({ notification, onMarkAsRead }) => {
    const { id, type, read, from_user, notifiable, created_at } = notification;

    const getNotificationText = () => {
        switch (type) {
            case 'comment':
                return 'оставил комментарий к вашему посту';
            case 'bookmark':
                return 'добавил ваш пост в закладки';
            case 'rating':
                return 'оценил ваш пост';
            default:
                return 'взаимодействовал с вашим постом';
        }
    };

    const getNotificationLink = () => {
        if (!notifiable) return '#';
        
        switch (notifiable.type) {
            case 'App\\Models\\Post':
                return `/posts/${notifiable.id}`;
            case 'App\\Models\\Comment':
                return `/posts/${notifiable.post_id}#comment-${notifiable.id}`;
            default:
                return '#';
        }
    };

    return (
        <div className={`notification-item ${!read ? 'notification-item--unread' : ''}`}>
            <div className="notification-item__content">
                <Link to={`/users/${from_user.id}`} className="notification-item__user">
                    <img 
                        src={from_user.avatar || '/images/default-avatar.png'} 
                        alt={from_user.name}
                        className="notification-item__avatar"
                    />
                    <span>{from_user.name}</span>
                </Link>
                <span className="notification-item__text">
                    {getNotificationText()}
                </span>
                <Link to={getNotificationLink()} className="notification-item__link">
                    {notifiable?.title || 'Перейти'}
                </Link>
            </div>
            <div className="notification-item__meta">
                <span className="notification-item__date">
                    {new Date(created_at).toLocaleDateString('ru-RU')}
                </span>
                {!read && (
                    <button 
                        className="notification-item__mark-read"
                        onClick={() => onMarkAsRead(id)}
                    >
                        Отметить как прочитанное
                    </button>
                )}
            </div>
        </div>
    );
};

export default NotificationItem; 