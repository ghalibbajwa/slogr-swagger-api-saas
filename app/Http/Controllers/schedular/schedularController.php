<?php

namespace App\Http\Controllers\schedular;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\analytics;
use App\sessions;
use App\Http\Controllers\WebsiteController\sessionController;

class schedularController extends Controller
{
    function schedule()
    {

        $scontol = new sessionController;
        $now = Carbon::now();
        $session = sessions::all();

        foreach ($session as $s) {
            $count = $s->count;
            $le = $s->updated_at;
            $sch = $s->schedule;
            $diff = $le->diffInSeconds($now);
            // dd($diff,$sch);
            if ($count <= 0) {
                if ($diff > $sch) {

                    $scontol->create_session($s);
                    $curr = sessions::find($s->id);
                    $count = $count - 1;
                    $curr->count = $count;
                    $curr->save();
                    // dd($curr->count);
                }
            } elseif ($count > 1) {
                $scontol->create_session($s);
                $curr = sessions::find($s->id);

                $count = $count - 1;

                $curr->count = $count;
                $curr->save();

            }

        }
    }
}