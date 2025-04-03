import React from 'react';
import PostCard from '../posts/PostCard';

const BookmarkList = ({ bookmarks, isLoading, onRemove }) => {
    if (isLoading) {
        return <div className="loading">Загрузка...</div>;
    }

    if (!bookmarks.length) {
        return <div className="no-bookmarks">У вас пока нет закладок</div>;
    }

    return (
        <div className="bookmark-list">
            {bookmarks.map(bookmark => (
                <div key={bookmark.id} className="bookmark-item">
                    <PostCard post={bookmark.post} />
                    <button 
                        className="bookmark-item__remove"
                        onClick={() => onRemove(bookmark.id)}
                    >
                        Удалить из закладок
                    </button>
                </div>
            ))}
        </div>
    );
};

export default BookmarkList; 