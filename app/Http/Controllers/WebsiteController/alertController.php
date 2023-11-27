<?php

namespace App\Http\Controllers\WebsiteController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\alerts;
use App\sessions;
use App\profiles;
use App\analytics;



class alertController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/alerts",
     *     summary="Get alerts",
     *     description="Retrieve all alerts",
     *     tags={"Alerts"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="alerts",
     *                     type="array",
     *                     @OA\Items()
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {

        $alerts = alerts::all();


        return response()->json($alerts)->setStatusCode(200);

    }




    /**
     * @OA\Post(
     *     path="/api/add-alert",
     *     summary="Add an Alert",
     *     description="Add or Edit and Alert",
     *     tags={"Alerts"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Alert Information",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="name for the alert"
     *             ),
     *             @OA\Property(
     *                 property="session",
     *                 type="integer",
     *                 description="Session id for the session to me monitored",
     *                 example="1"
     *             ),
     *              @OA\Property(
     *                 property="endpoint",
     *                 type="string",
     *                 description="endpoint on which the alert would be relayed",
     *                 example="localhost/api/alertreciever"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="New Alert Added"
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'profile' => 'required|numeric',
            'group' => 'required|numeric',
            'sla_breach_minutes' => 'numeric',
            'sla_breach_tests' => 'numeric',
            'tests_norun' => 'numeric',

        ]);



        // dd($validator->fails()); 
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(400);
        }

        if ($request->edit == true) {
            $alert = alerts::find($request->id);
        } else {

            $alert = new alerts;
        }

        $alert->name = $request->name;
        $alert->email = $request->email;
        $alert->group_id = $request->group;
        $alert->profile_id = $request->profile;

        if ($request->sla_breach_minutes) {
            $alert->sla_breach_minutes = $request->sla_breach_minutes;
        }
        if ($request->sla_breach_tests) {
            $alert->sla_breach_tests = $request->sla_breach_tests;
        }
        if ($request->tests_norun) {
            $alert->tests_norun = $request->tests_norun;
        }



        $alert->save();


        return response()->json($alert)->setStatusCode(200);
    }


    public function delete(Request $request)
    {

        $alert = alerts::find($request->id);

        if ($alert) {
            $alert->delete();

            return response()->json(['success' => "alert deleted"])->setStatusCode(200);
        } else {
            return response()->json(['error' => "alert not found"])->setStatusCode(400);

        }

    }





    public function tests_norun($sessions, $count)
    {
        $res = [];
        foreach ($sessions as $s) {

            $latestRows = analytics::where('session_id', $s->id)
                ->orderBy('created_at', 'desc')
                ->take($count)
                ->get();
            $avgs['avg_rtt'] = 0;

            $count = count($latestRows);
            foreach ($latestRows as $results) {
                $avgs['avg_rtt'] = $avgs['avg_rtt'] + $results['avg_rtt'];
            }


            if ($avgs['avg_rtt'] == 0) {
                $res = "red";
            } else {
                $res = "green";
            }

        }

        return $res;
    }

    public function sla_breach_tests($profile, $sessions, $tests)
    {
        $res = [];
        foreach ($sessions as $s) {

            $latestRows = analytics::where('session_id', $s->id)
                ->orderBy('created_at', 'desc')
                ->take($tests)
                ->get();
            $avgs['avg_rtt'] = 0;
            $avgs['avg_down'] = 0;
            $avgs['avg_jitter'] = 0;
            $avgs['avg_up'] = 0;

            $count = count($latestRows);
            foreach ($latestRows as $results) {
                $avgs['avg_rtt'] = $avgs['avg_rtt'] + $results['avg_rtt'];
                $avgs['avg_down'] = $avgs['avg_down'] + $results['avg_down'];
                $avgs['avg_jitter'] = $avgs['avg_jitter'] + $results['avg_jitter'];
                $avgs['avg_up'] = $avgs['avg_up'] + $results['avg_up'];
            }

            $avgs['avg_rtt'] = $avgs['avg_rtt'] / $count;
            $avgs['avg_down'] = $avgs['avg_down'] / $count;
            $avgs['avg_jitter'] = $avgs['avg_jitter'] / $count;
            $avgs['avg_up'] = $avgs['avg_up'] / $count;

            $sla = "green";
            if (($avgs['avg_rtt'] < $profile->rtt_g) || ($avgs['avg_down'] < $profile->downlink_g) || ($avgs['avg_up'] < $profile->uplink_g) && ($avgs['avg_jitter'] < $profile->jitter_g)) {
                $sla = "green";
            } elseif (($avgs['avg_rtt'] > $profile->rtt_g && $avgs['avg_rtt'] < $profile->rtt_r) || ($avgs['avg_down'] > $profile->downlink_g && $avgs['avg_down'] < $profile->downlink_r) || ($avgs['avg_up'] > $profile->uplink_g && $avgs['avg_up'] < $profile->uplink_r) && ($avgs['avg_jitter'] > $profile->jitter_g && $avgs['avg_jitter'] < $profile->jitter_r)) {
                $sla = "yellow";
            } elseif (($avgs['avg_rtt'] > $profile->rtt_r) || ($avgs['avg_down'] > $profile->downlink_r) || ($avgs['avg_up'] > $profile->uplink_r) && ($avgs['avg_jitter'] > $profile->jitter_r)) {
                $sla = "red";
            }

            $res[$s->id] = $sla;

        }


        return $res;

    }
    public function sla_breach_minutes($profile, $sessions, $minutes)
    {
        $res = [];
        foreach ($sessions as $s) {

            $latestRows = analytics::where('session_id', $s->id)
                ->where('created_at', '>=', now()->subMinutes($minutes))
                ->orderBy('created_at', 'desc')
                ->get();
            $avgs['avg_rtt'] = 0;
            $avgs['avg_down'] = 0;
            $avgs['avg_jitter'] = 0;
            $avgs['avg_up'] = 0;

            $count = count($latestRows);
            foreach ($latestRows as $results) {
                $avgs['avg_rtt'] = $avgs['avg_rtt'] + $results['avg_rtt'];
                $avgs['avg_down'] = $avgs['avg_down'] + $results['avg_down'];
                $avgs['avg_jitter'] = $avgs['avg_jitter'] + $results['avg_jitter'];
                $avgs['avg_up'] = $avgs['avg_up'] + $results['avg_up'];
            }

            $avgs['avg_rtt'] = $avgs['avg_rtt'] / $count;
            $avgs['avg_down'] = $avgs['avg_down'] / $count;
            $avgs['avg_jitter'] = $avgs['avg_jitter'] / $count;
            $avgs['avg_up'] = $avgs['avg_up'] / $count;

            $sla = "green";
            if (($avgs['avg_rtt'] < $profile->rtt_g) || ($avgs['avg_down'] < $profile->downlink_g) || ($avgs['avg_up'] < $profile->uplink_g) && ($avgs['avg_jitter'] < $profile->jitter_g)) {
                $sla = "green";
            } elseif (($avgs['avg_rtt'] > $profile->rtt_g && $avgs['avg_rtt'] < $profile->rtt_r) || ($avgs['avg_down'] > $profile->downlink_g && $avgs['avg_down'] < $profile->downlink_r) || ($avgs['avg_up'] > $profile->uplink_g && $avgs['avg_up'] < $profile->uplink_r) && ($avgs['avg_jitter'] > $profile->jitter_g && $avgs['avg_jitter'] < $profile->jitter_r)) {
                $sla = "yellow";
            } elseif (($avgs['avg_rtt'] > $profile->rtt_r) || ($avgs['avg_down'] > $profile->downlink_r) || ($avgs['avg_up'] > $profile->uplink_r) && ($avgs['avg_jitter'] > $profile->jitter_r)) {
                $sla = "red";
            }

            $res[$s->id] = $sla;

        }
        return $res;


    }

}