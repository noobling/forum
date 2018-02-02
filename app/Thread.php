<?php

namespace App;

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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies()->each(function($reply) {
                $reply->delete();
            });
        });

    }

    /**
     * Get an endpoint for the thread
     * 
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
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
     * @return Reply
     */
    public function addReply($reply)
    {
        return $this->replies()->create($reply);
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
}
