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
use App\report_data;
use App\reports;
use App\latest_analytics;

class schedularController extends Controller
{
    function schedule()
    {
        ini_set('max_execution_time', 600);
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
                        'body' =>  implode(", ", $sla_breach_minutes),
                        'sessions' => $sessions
                    ];
                    $report_data = new report_data();
                    $report_data->alert_id=$al->id;
                    $report_data->type = 'sla_breach_minutes';
                    $report_data->result =  implode(", ", $sla_breach_minutes);
                    $report_data->save();
                    $email->sendTestEmail($details,$al->email);
                }


            }
            if ($al->sla_breach_tests != null) {
                $sla_breach_tests = $alertcontroller->sla_breach_tests($profile, $sessions, $al->sla_breach_tests);
                if (in_array("red", $sla_breach_tests)) {
                    $details = [
                        'title' => 'SLA breached',
                        'body' =>  implode(", ", $sla_breach_tests),
                        'sessions' => $sessions
                    ];

                    $report_data = new report_data();
                    $report_data->alert_id=$al->id;
                    $report_data->type = 'sla_breach_tests';
                    $report_data->result =  implode(", ", $sla_breach_tests);
                    $report_data->save();
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
                    $report_data = new report_data();
                    $report_data->alert_id=$al->id;
                    $report_data->type = 'tests_norun';
                    $report_data->result =  implode(", ", $sessions);
                    $report_data->save();
                    $email->sendTestEmail($details,$al->email);
                }
            }

        }



        $reports = reports::all();
        foreach ($reports as $re) {
            $currentTime = now();
            $marginStart = $currentTime->copy()->subMinutes(2);
            $marginEnd = $currentTime->copy()->addMinutes(2);
            $reTime = Carbon::parse($re->time);
            $timeDifference = $reTime->diff($currentTime);
            $minutesDifference = $timeDifference->i;


            $upTime = Carbon::parse($re->updated_at);
            $uptimeDifference = $upTime->diff($currentTime);
            $hourssDifference = $uptimeDifference->h;




            if ($hourssDifference >= 23 && ($minutesDifference < 5 || $minutesDifference > -5)) {

                $mails = [];
                $alerts = $re->alerts;
                if ($alerts) {

                    foreach ($alerts as $al) {
                        array_push($mails, report_data::where('alert_id', '=', $al->id)->select('result')->get());
                    }
                }
                if (count($mails) > 0) {
                    $details = [
                        'title' => $re->name,
                        'body' => implode(", ", $mails)
                        
                    ];

                    $email->sendTestEmail($details, $re->email);
                }

                $rep = reports::find($re->id);
                $rep->updated_at = Carbon::now();
                $rep->save();
            }


        }






    }
}