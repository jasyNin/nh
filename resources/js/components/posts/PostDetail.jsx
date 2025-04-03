import React from 'react';
import { Link } from 'react-router-dom';
import CommentList from '../comments/CommentList';
import CreateCommentForm from '../comments/CreateCommentForm';

const PostDetail = ({ post, onCommentSubmit }) => {
    const { id, title, content, type, user, tags, created_at, comments } = post;

    return (
        <article className="post-detail">
            <div className="post-detail__header">
                <div className="post-detail__meta">
                    <Link to={`/users/${user.id}`} className="post-detail__author">
                        <img 
                            src={user.avatar || '/images/default-avatar.png'} 
                            alt={user.name}
                            className="post-detail__avatar"
                        />
                        <span>{user.name}</span>
                    </Link>
                    <span className="post-detail__date">
                        {new Date(created_at).toLocaleDateString('ru-RU')}
                    </span>
                </div>
                <span className={`post-detail__type post-detail__type--${type}`}>
                    {type === 'question' ? 'Вопрос' : 'Запись'}
                </span>
            </div>

            <div className="post-detail__content">
                <h1 className="post-detail__title">{title}</h1>
                <div className="post-detail__text">{content}</div>
            </div>

            {tags && tags.length > 0 && (
                <div className="post-detail__tags">
                    {tags.map(tag => (
                        <Link 
                            key={tag.id} 
                            to={`/tags/${tag.name}`}
                            className="post-detail__tag"
                        >
                            #{tag.name}
                        </Link>
                    ))}
                </div>
            )}

            <div className="post-detail__comments">
                <h2 className="post-detail__comments-title">
                    Комментарии ({comments.length})
                </h2>
                <CreateCommentForm onSubmit={onCommentSubmit} postId={id} />
                <CommentList comments={comments} />
            </div>
        </article>
    );
};

export default PostDetail; 