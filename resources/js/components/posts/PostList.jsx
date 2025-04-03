import React from 'react';
import { Link } from 'react-router-dom';
import PostCard from './PostCard';

const PostList = ({ posts, isLoading }) => {
    if (isLoading) {
        return <div className="loading">Загрузка...</div>;
    }

    if (!posts.length) {
        return <div className="no-posts">Постов пока нет</div>;
    }

    return (
        <div className="post-list">
            {posts.map(post => (
                <PostCard key={post.id} post={post} />
            ))}
        </div>
    );
};

export default PostList; 