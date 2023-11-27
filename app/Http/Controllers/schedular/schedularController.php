<?php

namespace App\Http\Controllers\schedular;

use App\groups;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\analytics;
use App\sessions;
use App\Http\Controllers\WebsiteController\sessionController;
use App\alerts;
use App\Http\Controllers\WebsiteController\alertController;
use App\profiles;
use App\Http\Controllers\WebsiteController\emailController;

class schedularController extends Controller
{
    function schedule()
    {
        #sessions
        // $scontol = new sessionController;
        // $now = Carbon::now();
        // $session = sessions::all();

        // foreach ($session as $s) {
        //     $count = $s->count;
        //     $le = $s->updated_at;
        //     $sch = $s->schedule;
        //     $diff = $le->diffInSeconds($now);
        //     // dd($diff,$sch);
        //     if ($count <= 0) {
        //         if ($diff > $sch) {

        //             $scontol->create_session($s);
        //             $curr = sessions::find($s->id);
        //             $count = $count - 1;
        //             $curr->count = $count;
        //             $curr->save();
        //             // dd($curr->count);
        //         }
        //     } elseif ($count > 1) {
        //         $scontol->create_session($s);
        //         $curr = sessions::find($s->id);

        //         $count = $count - 1;

        //         $curr->count = $count;
        //         $curr->save();

        //     }

        // }

        #alerts
        $alerts = alerts::all();
        $alertcontroller = new alertController();
        $email = new emailController();
        foreach ($alerts as $al) {
            $profile = profiles::find($al->profile_id);
            $sessions = groups::find($al->group_id)->sessions;
            $sla_breach_minutes;
            $sla_breach_tests;
            $tests_norun;
            if ($al->sla_breach_minutes != null) {
                $sla_breach_minutes = $alertcontroller->sla_breach_minutes($profile, $sessions, $al->sla_breach_minutes);

                if (in_array("red", $sla_breach_minutes)) {
                    $details = [
                        'title' => 'SLA breached',
                        'body' =>  implode(", ", $sla_breach_minutes)
                    ];
                    $email->sendTestEmail($details,$al->email);
                }


            }
            if ($al->sla_breach_tests != null) {
                $sla_breach_tests = $alertcontroller->sla_breach_tests($profile, $sessions, $al->sla_breach_tests);
                if (in_array("red", $sla_breach_tests)) {
                    $details = [
                        'title' => 'SLA breached',
                        'body' =>  implode(", ", $sla_breach_tests)
                    ];
                    $email->sendTestEmail($details,$al->email);
                }
            }
            if ($al->tests_norun != null) {
                $tests_norun = $alertcontroller->tests_norun($sessions, $al->tests_norun);
                if ($tests_norun=="red") {
                    $details = [
                        'title' => 'Tests not running',
                        'body' =>  implode(", ",  $sessions)
                    ];
                    $email->sendTestEmail($details,$al->email);
                }
            }

        }


    }
}