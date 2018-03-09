<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Only the user that created the thread can update it
     *
     * @param User $user
     * @param Reply $reply
     * @return bool
     */
    public function update(User $user, Reply $reply)
    {
        return $user->id == $reply->user_id;
    }

    /**
     * Replies can only be created if user has not replied recently
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        if (! $user->fresh()->lastReply) { return true; }

        return ! $user->lastReply->wasPublishedRecently();
    }
}
