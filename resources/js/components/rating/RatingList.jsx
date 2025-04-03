import React from 'react';
import { Link } from 'react-router-dom';

const RatingList = ({ users }) => {
    

    if (!users.length) {
        return <div className="no-users">Пользователей пока нет</div>;
    }

    return (
        <div className="rating-list">
            {users.map((user, index) => (
                <div key={user.id} className="rating-item">
                    <div className="rating-item__rank">
                        {index + 1}
                    </div>
                    <Link to={`/users/${user.id}`} className="rating-item__user">
                        <img 
                            src={user.avatar || '/images/default-avatar.png'} 
                            alt={user.name}
                            className="rating-item__avatar"
                        />
                        <div className="rating-item__info">
                            <span className="rating-item__name">{user.name}</span>
                            <span className="rating-item__rank-name">{user.rank}</span>
                        </div>
                    </Link>
                    <div className="rating-item__rating">
                        {user.rating} очков
                    </div>
                </div>
            ))}
        </div>
    );
};

export default RatingList; 