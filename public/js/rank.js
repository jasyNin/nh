function displayRankIcon(user) {
    const rankIcon = document.createElement('img');
    rankIcon.src = `/images/${user.rank_icon}`;
    rankIcon.alt = user.rank_name;
    rankIcon.title = user.rank_name;
    rankIcon.className = 'rank-icon';
    return rankIcon;
}

// Пример использования:
// const userRankIcon = displayRankIcon({
//     rank_icon: 'novichec.svg',
//     rank_name: 'Новичок'
// });
// document.querySelector('.user-avatar').appendChild(userRankIcon); 