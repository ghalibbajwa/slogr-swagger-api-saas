<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\reports;
class alerts extends Model
{
    public function reports()
    {
        return $this->belongsToMany(reports::class, 'alerts_reports', 'report_id', 'alert_id');
    }
}
