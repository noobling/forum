<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    public function owner()
    {
        // Have to explicit with the foreign key id since we are not using user but owner
        return $this->belongsTo(User::class, 'user_id');
    }
}
