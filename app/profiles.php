<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class profiles extends Model
{
    public function groups()
    {
        return $this->morphToMany(groups::class, 'groupable');
    }
}
