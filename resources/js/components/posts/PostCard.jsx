import React from 'react';
import { Link } from 'react-router-dom';

const PostCard = ({ post }) => {
    const { id, title, content, type, user, tags, created_at } = post;

    return (
        <article className="post-card">
            <div className="post-card__header">
                <div className="post-card__meta">
                    <Link to={`/users/${user.id}`} className="post-card__author">
                        <img 
                            src={user.avatar || '/images/default-avatar.png'} 
                            alt={user.name}
                            className="post-card__avatar"
                        />
                        <span>{user.name}</span>
                    </Link>
                    <span className="post-card__date">
                        {new Date(created_at).toLocaleDateString('ru-RU')}
                    </span>
                </div>
                <span className={`post-card__type post-card__type--${type}`}>
                    {type === 'question' ? 'Вопрос' : 'Запись'}
                </span>
            </div>

            <Link to={`/posts/${id}`} className="post-card__content">
                <h2 className="post-card__title">{title}</h2>
                <p className="post-card__excerpt">
                    {content.length > 200 ? `${content.substring(0, 200)}...` : content}
                </p>
            </Link>

            {tags && tags.length > 0 && (
                <div className="post-card__tags">
                    {tags.map(tag => (
                        <Link 
                            key={tag.id} 
                            to={`/tags/${tag.name}`}
                            className="post-card__tag"
                        >
                            #{tag.name}
                        </Link>
                    ))}
                </div>
            )}
        </article>
    );
};

export default PostCard; 