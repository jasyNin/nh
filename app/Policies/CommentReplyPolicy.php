<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CommentReply;

class CommentReplyPolicy
{
    public function update(User $user, CommentReply $reply): bool
    {
        return $user->id === $reply->user_id;
    }

    public function delete(User $user, CommentReply $reply): bool
    {
        return $user->id === $reply->user_id;
    }
} 