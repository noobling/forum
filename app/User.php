<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    /**
     * This is used for route model binding generally the key to search for is an id
     * however in our routes we don't want to use /endpoint/{id} instead this would
     * be nicer /endpoint/{name}
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Has many threads
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    /**
     * Has many activity
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     * @param $thread
     * @return string
     * @throws \Exception
     */
    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    /**
     * Update the required things when user has read a thread
     *
     * @param $thread
     * @throws \Exception
     */
    public function read($thread)
    {
        $key = $this->visitedThreadCacheKey($thread);
        cache()->forever($key, Carbon::now());
    }

    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ? '/storage/' .  $avatar: '/storage/avatars/default.jpg');
    }
}
