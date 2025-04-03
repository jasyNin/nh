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
                        @can('update', $reply)
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editReplyModal{{ $reply->id }}">
                                    Редактировать
                                </a>
                            </li>
                        @endcan
                        @can('delete', $reply)
                            <li>
                                <form action="{{ route('replies.destroy', $reply) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Вы уверены?')">
                                        Удалить
                                    </button>
                                </form>
                            </li>
                        @endcan
                        @cannot('update', $reply)
                            <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#reportReplyModal{{ $reply->id }}">
                                    Пожаловаться
                                </a>
                            </li>
                        @endcannot
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Пожаловаться на ответ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('replies.report', $reply) }}" method="POST" data-remote="true">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Тип жалобы</label>
                        <select name="type" class="form-select" required>
                            <option value="">Выберите тип жалобы</option>
                            <option value="спам">Спам</option>
                            <option value="оскорбление">Оскорбление</option>
                            <option value="неприемлемый контент">Неприемлемый контент</option>
                            <option value="нарушение авторских прав">Нарушение авторских прав</option>
                            <option value="другое">Другое</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Причина жалобы</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Опишите подробнее причину жалобы..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отправить жалобу</button>
                </div>
            </form>
        </div>
    </div>
</div> 