<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Forms\CreatePostForm;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Support\Facades\Gate;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index(Channel $channel, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    /**
     * Persist a new reply
     *
     * @param $channelId
     * @param Thread $thread
     * @param CreatePostForm $form
     * @return Reply
     */
    public function store($channelId, Thread $thread, CreatePostForm $form)
    {
        $reply = $thread->addReply([
            "body" => request('body'),
            "user_id" => auth()->id()
        ]);

        // Notify all mentioned users
        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);

        $names = $matches[1];

        foreach ($names as $name) {
            $user = User::whereName($name)->first();

            if ($user) {
                $user->notify(new YouWereMentioned($reply));
            }
        }

        if (\request()->wantsJson()) {
            return $reply->load('owner');
        }
        return back()
            ->with('flash', 'Created reply!');


    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (\request()->expectsJson()) {
            return response(['status' => 'Reply delete']);
        }

        return back()
            ->with('flash', 'Deleted reply');
    }

    /**
     * @param Reply $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate([
            'body' => 'required|spamfree'
        ]);

        $reply->update(request(['body']));
    }
}
