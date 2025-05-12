<div class="comment-reply mb-3" id="reply-{{ $reply->id }}">
    <div class="d-flex">
        <div class="flex-shrink-0 me-3">
            <a href="{{ route('users.show', $reply->user) }}">
                <x-user-avatar :user="$reply->user" :size="32" />
            </a>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <a href="{{ route('users.show', $reply->user) }}" class="text-decoration-none text-dark fw-bold">{{ $reply->user->name }}</a>
                    <span class="text-muted ms-2">{{ $reply->created_at->diffForHumans() }}</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if(auth()->check() && auth()->id() === $reply->user_id)
                            <li>
                                <x-reply-edit-button :replyId="$reply->id" />
                            </li>
                            <li>
                                <x-reply-delete-button :replyId="$reply->id" />
                            </li>
                        @elseif(auth()->check() && auth()->id() !== $reply->user_id)
                            <li>
                                <x-reply-report-button :replyId="$reply->id" />
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="reply-content">
                {{ $reply->content }}
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для жалобы на ответ -->
<div class="modal fade" id="reportReplyModal{{ $reply->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="border-bottom: none;">
                <h5 class="modal-title" style="font-size: 22px; font-weight: 500;">Пожаловаться на ответ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('replies.report', $reply) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 17px; font-weight: 400;">Тип жалобы</label>
                        <select name="type" class="form-select" required style="background-color: #F5F5F5; height: 48px; border-radius: 12px;">
                            <option value="" style="color: #808080;">Выберите причину</option>
                            <option value="spam" style="color: #272727;">Спам</option>
                            <option value="insult" style="color: #272727;">Оскорбление</option>
                            <option value="inappropriate" style="color: #272727;">Неприемлемый контент</option>
                            <option value="copyright" style="color: #272727;">Нарушение авторских прав</option>
                            <option value="violence" style="color: #272727;">Насилие</option>
                            <option value="hate_speech" style="color: #272727;">Разжигание ненависти</option>
                            <option value="fake_news" style="color: #272727;">Фейковые новости</option>
                            <option value="other" style="color: #272727;">Другое</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 17px; font-weight: 400;">Описание</label>
                        <textarea name="reason" class="form-control" rows="3" required minlength="10" placeholder="Опишите подробнее причину жалобы..." style="background-color: #F5F5F5; height: 88px; border-radius: 12px; color: #272727;"></textarea>
                        <div class="form-text">Минимум 10 символов</div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none; display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn" style="background-color: #1682FD; color: white;">Отправить жалобу</button>
                </div>
            </form>
        </div>
    </div>
</div> 