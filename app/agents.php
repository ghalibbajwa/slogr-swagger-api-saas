<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class agents extends Model
{
    //

    public function sessions()
    {
        return $this->hasMany(sessions::class);
    }
    public function groups()
    {
        return $this->morphToMany(groups::class, 'groupable');
    }
}
