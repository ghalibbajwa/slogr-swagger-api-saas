<?php

namespace App\Http\Controllers\WebsiteController;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\sessions;
use App\analytics;
use App\agents;
use App\profiles;

class analyticController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/analytics/{sid}",
     *     summary="Get session details",
     *     description="Retrieve session details by ID",
     *     tags={"Sessions"},
     *     @OA\Parameter(
     *         name="sid",
     *         in="path",
     *         description="Session ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="analytic",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="client",
     *                     type="object",
     *                 ),
     *                 @OA\Property(
     *                     property="server",
     *                     type="object",
     *                     
     *                 ),
     *                 @OA\Property(
     *                     property="rtt",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="up",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="down",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="profile",
     *                     type="object",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    function index($sid)
    {
        $session = sessions::find($sid);
        $client = agents::find($session->client);
        $server = agents::find($session->server);
        $profile = profiles::find($session->profile);
        $rtt = [];

        $analytic = analytics::where('session_id', '=', $sid)->orderBy('updated_at', 'DESC')->get();

        $rtt = array();
        $up = array();
        $down = array();
        $count = 0;
        $date = Carbon::now();

        foreach ($analytic as $an) {
            $up[$count] = [
                'date' => strtotime($date),
                'value' => $an->avg_up,
            ];
            $down[$count] = [
                'date' => strtotime($date),
                'value' => $an->avg_down,
            ];

            $rtt[$count] = [
                'date' => strtotime($date),
                'value' => $an->avg_rtt,
            ];
            $date->addDay();
            $count += 1;

        }

        // dd($analytic);

        $data['analytic'] = $analytic;
        $data['client'] = $client;
        $data['server'] = $server;
        $data['rtt'] = $rtt;
        $data['up'] = $up;
        $data['down'] = $down;
        $data['profile'] = $profile;
        return response()->json(['data' => $data])->setStatusCode(200);
    }


    /**
     * @OA\Get(
     *     path="/api/agentlogs/{id}",
     *     summary="Get agent logs",
     *     description="Retrieve agent logs",
     *     tags={"Agents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Agent ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="logs",
     *                     type="array",
     *                     @OA\Items()
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=300,
     *         description="Client Unreachable",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */

    function agentlogs($id)
    {

        $agent = agents::find($id);
        $client = new Client();
        try {

            $response = $client->get($agent->ipaddress . ':5000/logs');
        } catch (\Exception $e) {

            return response()->json(['error' => "Client Unreachable"])->setStatusCode(300);
        }

        $logs = $response->getBody()->getContents();
        $logs = explode("\n", $logs);
        // dd($logs);
        $data['logs'] = $logs;
        return response()->json(['data' => $data])->setStatusCode(200);


    }

    /**
     * @OA\Get(
     *     path="/api/agentdata/{id}/{profile}",
     *     summary="Get agent data",
     *     description="Retrieve agent data",
     *     tags={"Agents"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Agent ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="pro",
     *         in="path",
     *         description="Profile ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="object",
     *                 @OA\Property(
     *                     property="res",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="agent",
     *                     type="object"
     *                 ),
     *                 @OA\Property(
     *                     property="pros",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="profile",
     *                     type="object"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=300,
     *         description="Client Unreachable",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string"
     *             )
     *         )
     *     )
     * )
     */
    function agentdata($id, $pro)
    {
        $agent = agents::find($id);
        $pros = profiles::all();
        $profile = profiles::find($pro);
        $client = new Client();
        try {

            $response = $client->get($agent->ipaddress . ':5000/getall');
        } catch (\Exception $e) {
            return response()->json(['error' => "Client Unreachable"])->setStatusCode(300);

        }
        $contents = $response->getBody()->getContents();
        $array = json_decode($contents, true);
        $res = collect([]);
        foreach ($array as $array) {

            $analytics = new Analytics;

            $analytics->avg_down = $array['avg-downlink-time(ms)'];
            $analytics->avg_jitter = $array['avg-jitter(ms)'];
            $analytics->avg_rtt = $array['avg-rtt(ms)'];
            $analytics->avg_up = $array['avg-uplink-time(ms)'];
            $analytics->max_down = $array['max-downlink-time(ms)'];
            $analytics->max_jitter = $array['max-jitter(ms)'];
            $analytics->max_rtt = $array['max-rtt(ms)'];
            $analytics->max_up = $array['max-uplink-time(ms)'];
            $analytics->min_down = $array['min-downlink-time(ms)'];
            $analytics->min_jitter = $array['min-jitter(ms)'];
            $analytics->min_rtt = $array['min-rtt(ms)'];
            $analytics->min_up = $array['min-uplink-time(ms)'];
            $analytics->r_packets = $array['received-packets'];
            $analytics->st_down = $array['std-dev-downlink-time(ms)'];
            $analytics->st_up = $array['std-dev-uplink-time(ms)'];
            $analytics->st_rtt = $array['std-dev-rtt(ms)'];
            $analytics->t_packets = $array['total-packets'];

            if ($profile->max_rtt > $analytics->avg_rtt or $profile->max_jitter > $analytics->avg_jitter or $profile->max_loss > ($analytics->r_packets - $analytics->t_packets)) {
                $analytics->sla = "breached";
            } else {
                $analytics->sla = "not";
            }


            $res[] = $analytics;

        }

        // dd($res);
        $success['res'] = $res;
        $success['agent'] = $agent;
        $success['pros'] = $pros;
        $success['profile'] = $profile;


        return response()->json(['success' => $success])->setStatusCode(200);

    }
}