<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 22/01/2018
 * Time: 12:32 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

trait Favouritable
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favourited');
    }

    /**
     * @return Model
     */
    public function favourite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favourites()->where($attributes)->exists()) {
            return $this->favourites()->create($attributes);
        }
    }

    /**
     * Has the current signed in user already favourited this reply?
     *
     * @return bool
     */
    public function isFavourited()
    {
        return !!$this->favourites->where('user_id', auth()->id())->count();
    }

    public function getFavouritesCountAttribute()
    {
        return $this->favourites->count();
    }
}