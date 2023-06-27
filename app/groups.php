<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class groups extends Model
{
    
    public function agents()
    {
        return $this->morphedByMany(agents::class, 'groupable');
    }

    public function profiles()
    {
        return $this->morphedByMany(profiles::class, 'groupable');
    }

    public function sessions()
    {
        return $this->morphedByMany(sessions::class, 'groupable');
    }
}
