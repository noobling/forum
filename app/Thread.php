<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * A thread will always need info on the creator and channel
     *
     * @var array
     */
    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope('replyCount', function($builder) {
//            $builder->withCount('replies');
//        });

        static::deleting(function ($thread) {
            $thread->replies()->each(function ($reply) {
                $reply->delete();
            });
        });

        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);
        });

    }

    /**
     * Get an endpoint for the thread
     *
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    /**
     * A thread may have many replies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * A thread belongs to a creator
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread belongs to a chanel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Add a reply to a thread
     *
     * @param array $reply
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadHasNewReply($this, $reply));

        return $reply;
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * A user subscribing to a thread
     *
     * @param integer $userId
     * @return Model
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create(
            [
                'user_id' => $userId ?: auth()->id()
            ]
        );

        return $this;
    }

    /**
     * A thread has man subscriptions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * Unsubscribe from a thread
     *
     * @param integer $userId
     * @return mixed
     */
    public function unsubscribe($userId = null)
    {
        return $this->subscriptions()->where([
            'user_id' => $userId ?: auth()->id()
        ])
            ->delete();
    }

    /**
     * An attribute that checks if authenticated user is subscribed to the thread
     *
     * @return bool
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    /**
     * Returns whether thread was upadted since users last view
     *
     * @param $user
     * @return bool
     * @throws \Exception
     */
    public function hasUpdatesFor($user)
    {
        $key = sprintf("users.%s.visits.%s", $user->id, $this->id);

        return $this->updated_at > cache($key);
    }

    /**
     * Return an instance of the visit class
     *
     * @return Visits
     */
    public function visits()
    {
        return new Visits($this);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);
        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-" . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

}
