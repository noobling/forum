<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Notify all mentioned users
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        preg_match_all('/@([\w\-]+)/', $event->reply->body, $matches);

        $names = $matches[1];

        foreach ($names as $name) {
            $user = User::whereName($name)->first();

            if ($user) {
                $user->notify(new YouWereMentioned($event->reply));
            }
        }
    }
}
