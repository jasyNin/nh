import React from 'react';
import { Link } from 'react-router-dom';

const TagList = ({ tags, isLoading }) => {
    if (isLoading) {
        return <div className="loading">Загрузка...</div>;
    }

    if (!tags.length) {
        return <div className="no-tags">Тегов пока нет</div>;
    }

    return (
        <div className="tag-list">
            {tags.map(tag => (
                <Link 
                    key={tag.id} 
                    to={`/tags/${tag.name}`}
                    className="tag-item"
                >
                    <span className="tag-item__name">#{tag.name}</span>
                    <span className="tag-item__count">
                        {tag.posts_count} {tag.posts_count === 1 ? 'запись' : 'записей'}
                    </span>
                </Link>
            ))}
        </div>
    );
};

export default TagList; 