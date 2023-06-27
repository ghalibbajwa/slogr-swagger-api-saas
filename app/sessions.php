<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sessions extends Model
{
    //
    public function agent()
    {
        return $this->belongsTo(agents::class);
    }
    public function groups()
    {
        return $this->morphToMany(groups::class, 'groupable');
    }
}