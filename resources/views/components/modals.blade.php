@props(['posts'])

<!-- Модальные окна для жалоб -->
@foreach($posts as $post)
<div class="modal fade" id="reportPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="reportPostModalLabel{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="border-bottom: none;">
                <h5 class="modal-title" id="reportPostModalLabel{{ $post->id }}">Пожаловаться на пост</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="complaint-form" data-complaintable-id="{{ $post->id }}" data-complaintable-type="App\\Models\\Post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="complaintType{{ $post->id }}" class="form-label">Тип жалобы</label>
                        <select class="form-select" id="complaintType{{ $post->id }}" name="type" required>
                            <option value="">Выберите тип жалобы</option>
                            <option value="spam">Спам</option>
                            <option value="inappropriate">Неприемлемый контент</option>
                            <option value="violence">Насилие</option>
                            <option value="copyright">Нарушение авторских прав</option>
                            <option value="other">Другое</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="complaintReason{{ $post->id }}" class="form-label">Причина жалобы</label>
                        <textarea class="form-control" id="complaintReason{{ $post->id }}" name="reason" rows="3" required minlength="10" placeholder="Опишите подробнее причину жалобы..."></textarea>
                        <div class="form-text">Минимум 10 символов</div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <div class="d-flex justify-content-between w-100">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn" style="background-color: #1682FD; color: white;">Отправить жалобу</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@foreach($posts as $post)
    @foreach($post->comments as $comment)
    <div class="modal fade" id="reportCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="reportCommentModalLabel{{ $comment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportCommentModalLabel{{ $comment->id }}">Пожаловаться на комментарий</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="complaint-form" data-complaintable-id="{{ $comment->id }}" data-complaintable-type="App\\Models\\Comment">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="complaintType{{ $comment->id }}" class="form-label">Тип жалобы</label>
                            <select class="form-select" id="complaintType{{ $comment->id }}" name="type" required>
                                <option value="">Выберите тип жалобы</option>
                                <option value="spam">Спам</option>
                                <option value="inappropriate">Неприемлемый контент</option>
                                <option value="violence">Насилие</option>
                                <option value="copyright">Нарушение авторских прав</option>
                                <option value="other">Другое</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="complaintReason{{ $comment->id }}" class="form-label">Описание жалобы</label>
                            <textarea class="form-control" id="complaintReason{{ $comment->id }}" name="reason" rows="3" required minlength="10" placeholder="Опишите причину жалобы (минимум 10 символов)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Отправить жалобу</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endforeach

<!-- Модальные окна для жалоб на ответы -->
@foreach($posts as $post)
    @foreach($post->comments as $comment)
        @foreach($comment->replies as $reply)
        <div class="modal fade" id="reportReplyModal{{ $reply->id }}" tabindex="-1" aria-labelledby="reportReplyModalLabel{{ $reply->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportReplyModalLabel{{ $reply->id }}">Пожаловаться на ответ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="complaint-form" data-complaintable-id="{{ $reply->id }}" data-complaintable-type="App\\Models\\Reply">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="complaintType{{ $reply->id }}" class="form-label">Тип жалобы</label>
                                <select class="form-select" id="complaintType{{ $reply->id }}" name="type" required>
                                    <option value="">Выберите тип жалобы</option>
                                    <option value="spam">Спам</option>
                                    <option value="inappropriate">Неприемлемый контент</option>
                                    <option value="violence">Насилие</option>
                                    <option value="copyright">Нарушение авторских прав</option>
                                    <option value="other">Другое</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="complaintReason{{ $reply->id }}" class="form-label">Описание жалобы</label>
                                <textarea class="form-control" id="complaintReason{{ $reply->id }}" name="reason" rows="3" required minlength="10" placeholder="Опишите причину жалобы (минимум 10 символов)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary">Отправить жалобу</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endforeach
@endforeach 