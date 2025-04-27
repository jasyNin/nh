let loading = false;
let skip = 10;

function loadMorePosts() {
    if (loading) return;
    loading = true;

    fetch(`/?skip=${skip}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.posts.length > 0) {
            const postsContainer = document.querySelector('.posts-container');
            data.posts.forEach(post => {
                // Создаем элемент для поста
                const postElement = document.createElement('div');
                postElement.innerHTML = `
                    <div class="post-card mb-4">
                        <div class="card border-0">
                            <div class="card-body p-4">
                                <!-- Информация о пользователе -->
                                <div class="d-flex align-items-center mb-4">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <a href="/users/${post.user.id}" class="text-decoration-none">
                                            <img src="${post.user.avatar || '/images/default-avatar.png'}" 
                                                 class="rounded-circle me-2" 
                                                 style="margin-right: 12px !important;"
                                                 width="48" 
                                                 height="48" 
                                                 alt="${post.user.name}">
                                        </a>
                                        <div>
                                            <a href="/users/${post.user.id}" class="text-decoration-none text-dark fw-bold">${post.user.name}</a>
                                            <div class="text-muted small">${post.created_at}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Заголовок и контент -->
                                <h2 class="h5 mb-3">
                                    <a href="/posts/${post.id}" class="text-decoration-none text-dark">
                                        ${post.title}
                                    </a>
                                </h2>
                                <div class="post-content mb-3">
                                    ${post.content}
                                </div>

                                <!-- Теги -->
                                ${post.tags.length > 0 ? `
                                    <div class="tags mb-3">
                                        ${post.tags.map(tag => `
                                            <a href="/tags/${tag.id}" class="badge bg-light text-dark text-decoration-none me-2">
                                                #${tag.name}
                                            </a>
                                        `).join('')}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
                postsContainer.appendChild(postElement);
            });

            if (data.hasMore) {
                skip += 10;
            } else {
                // Удаляем обработчик прокрутки, если больше нет постов
                window.removeEventListener('scroll', handleScroll);
            }
        }
    })
    .catch(error => console.error('Error:', error))
    .finally(() => {
        loading = false;
    });
}

function handleScroll() {
    if ((window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight - 1000) {
        loadMorePosts();
    }
}

// Добавляем обработчик прокрутки
window.addEventListener('scroll', handleScroll); 