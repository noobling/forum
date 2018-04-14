<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favouritable;
    use RecordsActivity;

    /**
     * Don't apply mass assignment protection
     */
    protected $guarded = [];

    // Every query for a reply will fetch these
    protected $with = ['owner', 'favourites'];

    /**
     * Passes these properties to Vue
     *
     * @var array
     */
    protected $appends = ['favouritesCount', 'isFavourited'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($reply) {
            $reply->thread()->increment('replies_count');
        });

        static::deleting(function ($reply) {
            $reply->thread()->decrement('replies_count');
        });
    }


    /**
     * A reply belongs to an owner
     *
     * return \Illumintate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        // Have to explicit with the foreign key id since we are not using user but owner
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply belongs to a thread
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Get the path of a reply
     *
     * @return string
     */
    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    /**
     * A reply was published recently
     *
     * @return bool
     */
    public function wasPublishedRecently()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     * Do something to body attribute before saving it to db
     *
     * @param $body
     */
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }
}
