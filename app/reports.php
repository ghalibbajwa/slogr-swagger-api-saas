<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\alerts;
class reports extends Model
{
    public function alerts()
    {
        return $this->belongsToMany(alerts::class, 'alerts_reports', 'report_id', 'alert_id');
    }
}
