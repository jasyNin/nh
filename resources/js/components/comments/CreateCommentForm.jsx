import React, { useState } from 'react';

const CreateCommentForm = ({ onSubmit, postId }) => {
    const [content, setContent] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!content.trim()) return;

        onSubmit({
            post_id: postId,
            content: content.trim()
        });

        setContent('');
    };

    return (
        <form className="create-comment-form" onSubmit={handleSubmit}>
            <div className="form-group">
                <textarea
                    value={content}
                    onChange={(e) => setContent(e.target.value)}
                    placeholder="Напишите ваш комментарий..."
                    required
                    rows="3"
                />
            </div>
            <div className="form-actions">
                <button 
                    type="submit" 
                    className="btn btn-primary"
                    disabled={!content.trim()}
                >
                    Отправить
                </button>
            </div>
        </form>
    );
};

export default CreateCommentForm; 