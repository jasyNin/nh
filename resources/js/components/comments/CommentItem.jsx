import React from 'react';
import { Link } from 'react-router-dom';

const CommentItem = ({ comment }) => {
    const { id, content, user, created_at } = comment;

    return (
        <div className="comment-item">
            <div className="comment-item__header">
                <Link to={`/users/${user.id}`} className="comment-item__author">
                    <img 
                        src={user.avatar || '/images/default-avatar.png'} 
                        alt={user.name}
                        className="comment-item__avatar"
                    />
                    <span>{user.name}</span>
                </Link>
                <span className="comment-item__date">
                    {new Date(created_at).toLocaleDateString('ru-RU')}
                </span>
            </div>
            <div className="comment-item__content">
                {content}
            </div>
        </div>
    );
};

export default CommentItem; 