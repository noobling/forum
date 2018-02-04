<?php

namespace App;

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
    protected  $appends = ['favouritesCount', 'isFavourited'];

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

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }
}
