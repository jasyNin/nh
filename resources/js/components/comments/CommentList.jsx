import React from 'react';
import { Link } from 'react-router-dom';
import CommentItem from './CommentItem';

const CommentList = ({ comments }) => {
    if (!comments.length) {
        return <div className="no-comments">Комментариев пока нет</div>;
    }

    return (
        <div className="comment-list">
            {comments.map(comment => (
                <CommentItem key={comment.id} comment={comment} />
            ))}
        </div>
    );
};

export default CommentList; 