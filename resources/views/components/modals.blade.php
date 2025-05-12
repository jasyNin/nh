@props(['posts'])

<!-- Модальные окна для жалоб -->
@foreach($posts as $post)
<div class="modal fade" id="reportPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="reportPostModalLabel{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 8px;">
            <div class="modal-header" style="border-bottom: none;">
                <h2 class="modal-title" id="reportPostModalLabel{{ $post->id }}" style="font-size: 24px; font-weight: 500;">Пожаловаться на пост</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('moderator.complaints.store') }}" method="POST">
                @csrf
                <input type="hidden" name="complaintable_id" value="{{ $post->id }}">
                <input type="hidden" name="complaintable_type" value="App\Models\Post">
                <div class="modal-body">
                    <input type="hidden" name="target_type" value="post">
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
                        <label class="form-label" style="font-size: 17px; font-weight: 400;">Причина жалобы </label>
                        <textarea name="reason" class="form-control" rows="3" required minlength="10" 
                            placeholder="Опишите подробнее причину жалобы..." 
                            style="background-color: #F5F5F5; height: 88px; border-radius: 12px; color: #272727;"
                        ></textarea>
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
@endforeach

@foreach($posts as $post)
    @foreach($post->comments as $comment)
    <div class="modal fade" id="reportCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="reportCommentModalLabel{{ $comment->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header" style="border-bottom: none;">
                    <h5 class="modal-title" id="reportCommentModalLabel{{ $comment->id }}" style="font-size: 22px; font-weight: 500;">Пожаловаться на комментарий</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('moderator.complaints.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="complaintable_id" value="{{ $comment->id }}">
                    <input type="hidden" name="complaintable_type" value="App\\Models\\Comment">
                    <div class="modal-body">
                        <input type="hidden" name="target_type" value="comment">
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
                            <textarea class="form-control" name="reason" rows="3" required minlength="10" placeholder="Опишите подробнее причину жалобы..." style="background-color: #F5F5F5; height: 88px; border-radius: 12px; color: #272727;"></textarea>
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
    @endforeach
@endforeach

<!-- Модальные окна для жалоб на ответы -->
@foreach($posts as $post)
    @foreach($post->comments as $comment)
        @foreach($comment->replies as $reply)
        <div class="modal fade" id="reportReplyModal{{ $reply->id }}" tabindex="-1" aria-labelledby="reportReplyModalLabel{{ $reply->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 12px;">
                    <div class="modal-header" style="border-bottom: none;">
                        <h5 class="modal-title" id="reportReplyModalLabel{{ $reply->id }}" style="font-size: 22px; font-weight: 500;">Пожаловаться на ответ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('moderator.complaints.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="complaintable_id" value="{{ $reply->id }}">
                        <input type="hidden" name="complaintable_type" value="App\\Models\\Reply">
                        <div class="modal-body">
                            <input type="hidden" name="target_type" value="reply">
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
                                <textarea class="form-control" name="reason" rows="3" required minlength="10" placeholder="Опишите подробнее причину жалобы..." style="background-color: #F5F5F5; height: 88px; border-radius: 12px; color: #272727;"></textarea>
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
        @endforeach
    @endforeach
@endforeach 