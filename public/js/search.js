document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const searchContainer = document.querySelector('.search-container');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                searchContainer.classList.remove('has-results');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/search/posts?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(posts => {
                        const resultsContainer = document.createElement('div');
                        resultsContainer.className = 'search-results';

                        if (posts.length > 0) {
                            posts.forEach(post => {
                                const resultItem = document.createElement('a');
                                resultItem.href = post.url;
                                resultItem.className = 'search-result-item';
                                resultItem.innerHTML = `
                                    <div class="d-flex align-items-center">
                                        <img src="${post.user.avatar}" alt="${post.user.name}" class="rounded-circle me-2" width="32" height="32">
                                        <div>
                                            <div class="search-result-title">${post.title}</div>
                                            <div class="search-result-meta">
                                                ${post.user.name} • ${post.created_at}
                                            </div>
                                        </div>
                                    </div>
                                `;
                                resultsContainer.appendChild(resultItem);
                            });
                        } else {
                            resultsContainer.innerHTML = `
                                <div class="search-no-results">
                                    Ничего не найдено
                                </div>
                            `;
                        }

                        // Удаляем старые результаты
                        const oldResults = searchContainer.querySelector('.search-results');
                        if (oldResults) {
                            oldResults.remove();
                        }

                        // Добавляем новые результаты
                        searchContainer.appendChild(resultsContainer);
                        searchContainer.classList.add('has-results');
                    })
                    .catch(error => {
                        console.error('Ошибка при поиске:', error);
                    });
            }, 300);
        });

        // Закрытие результатов при клике вне
        document.addEventListener('click', function(event) {
            if (!searchContainer.contains(event.target)) {
                searchContainer.classList.remove('has-results');
            }
        });
    }
}); 