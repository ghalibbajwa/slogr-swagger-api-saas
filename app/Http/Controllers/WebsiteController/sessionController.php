<?php

namespace App\Http\Controllers\WebsiteController;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Validator;
use App\sessions;
use App\profiles;
use App\agents;
use GuzzleHttp\Client;
use GuzzleHttp;
use App\analytics;
use Config;
use App\alerts;
use App\Services\RabbitMQService;
use Illuminate\Routing\UrlGenerator;
use Crypt;


class sessionController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/sessions",
     *     summary="Get Sessions",
     *     description="Retrieve data for sessions, agents, profiles, and pros",
     *     tags={"Sessions"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="agents",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="sessions",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="profiles",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="pros",
     *                     type="object",
     *                     additionalProperties={}
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function index(Request $request)
    {

        $page = 1;
        $size = 10;
        if ($request->page) {
            $page = $request->page;
        }
        if ($request->size) {
            $size = $request->size;
        }



        $next = $page + 1;
        $prev = null;
        if ($page != 1) {
            $prev = $page - 1;
        }


        $sessions = sessions::orderBy('created_at', 'desc')->get();

        if ($request->c_name) {
            $c_name = $request->c_name;
            $filteredAgents = $sessions->filter(function ($sessions) use ($c_name) {
                return Str::contains($sessions->c_name, $c_name);
            });
            $sessions = $filteredAgents;
        }



        if ($request->s_name) {
            $s_name = $request->s_name;
            $filteredAgents = $sessions->filter(function ($sessions) use ($s_name) {
                return Str::contains($sessions->s_name, $s_name);
            });
            $sessions = $filteredAgents;
        }



        if ($request->p_name) {
            $p_name = $request->p_name;
            $filteredAgents = $sessions->filter(function ($sessions) use ($p_name) {
                return Str::contains($sessions->p_name, $p_name);
            });
            $sessions = $filteredAgents;
        }


        $sessionsarray = $sessions->toArray();

        $offset = ($page - 1) * $size;
        $sessionsForPagew = array_slice($sessionsarray, $offset, $size);


        $data['count'] = count($sessionsarray);
        $data['next'] = $next;
        $data['prev'] = $prev;
        $data['page-size'] = $size;
        $data['sessions'] = $sessionsForPagew;


        return response()->json(['data' => $data])->setStatusCode(200);

    }

    /**
     * @OA\Post(
     *     path="/api/add-session",
     *     summary="Create a session",
     *     description="Create a new session",
     *     tags={"Sessions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="serve",
     *                 type="integer",
     *                 description="Server ID",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 property="client",
     *                 type="integer",
     *                 description="Client ID",
     *                 example="2"
     *             ),
     *             @OA\Property(
     *                 property="profile",
     *                 type="integer",
     *                 description="Profile ID",
     *                 example="3"
     *             ),
     *             @OA\Property(
     *                 property="schedule",
     *                 type="integer",
     *                 description="Schedule",
     *                 example="180"
     *             ),
     *             @OA\Property(
     *                 property="count",
     *                 type="integer",
     *                 description="Count",
     *                 example="10"
     *             ),
     *             @OA\Property(
     *                 property="n_packets",
     *                 type="integer",
     *                 description="Number of packets",
     *                 example="100"
     *             ),
     *             @OA\Property(
     *                 property="p_interval",
     *                 type="integer",
     *                 description="Packet interval",
     *                 example="10"
     *             ),
     *             @OA\Property(
     *                 property="w_time",
     *                 type="integer",
     *                 description="Wait time",
     *                 example="5"
     *             ),
     *             @OA\Property(
     *                 property="dscp",
     *                 type="integer",
     *                 description="DSCP",
     *                 example="46"
     *             ),
     *             @OA\Property(
     *                 property="p_size",
     *                 type="integer",
     *                 description="Packet size",
     *                 example="1500"
     *             ),
     *             @OA\Property(
     *                 property="edit",
     *                 type="boolean",
     *                 description="Edit flag",
     *                 example="false"
     *             ),
     *             @OA\Property(
     *                 property="aid",
     *                 type="integer",
     *                 description="Session ID (required if edit flag is true)",
     *                 example="1"
     *             )
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
     *                     property="session",
     *                     
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=300,
     *         description="Validation error or client unreachable",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 description="Error message"
     *             )
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'serve' => 'required|numeric',
            'client' => 'required|numeric',
            'profile' => 'required|numeric',
            'schedule' => 'required|numeric|min:120',
            'count' => 'required|numeric',
            'n_packets' => 'required|numeric',
            'p_interval' => 'required|numeric',
            'w_time' => 'required|numeric',
            'dscp' => 'required|numeric',
            'p_size' => 'required|numeric'
        ]);



        // dd($validator->fails()); 
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(400);

        }

        if ($request->edit == true) {
            $session = sessions::find($request->aid);
            if ($session) {

            } else {
                return response()->json(['error' => "Session not found"])->setStatusCode(400);
            }
        } else {

            $session = new sessions;
        }


        $session->server = $request->serve;
        $session->s_name = agents::find($request->serve)->name;
        $session->c_name = agents::find($request->client)->name;
        $session->p_name = profiles::find($request->profile)->name;
        $session->client = $request->client;
        $session->profile = $request->profile;
        $session->schedule = $request->schedule;
        $session->count = $request->count;
        $session->n_packets = $request->n_packets;
        $session->p_interval = $request->p_interval;
        $session->w_time = $request->w_time;
        $session->dscp = $request->dscp;
        $session->p_size = $request->p_size;


        $session->save();
      
        $code = $this->create_session($session);
  
        if ($code == "suc") {

            $success['session'] = $session;


            return response()->json(['success' => $success])->setStatusCode(200);

        } else {
            return response()->json(['error' => 'client unreachable'])->setStatusCode(300);

        }

    }

    /**
     * @OA\Post(
     *     path="/api/delete-session",
     *     summary="Delete a session",
     *     description="Delete a session by ID",
     *     tags={"Sessions"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Session ID",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="delete",
     *                 type="integer",
     *                 description="ID of the session to be deleted"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="Status of the deletion operation"
     *             )
     *         )
     *     )
     * )
     */

    public function delete(Request $request)
    {



        $session = sessions::find($request->delete);

        if ($session) {

            $session->delete();
            return response()->json(['success' => "session deleted"])->setStatusCode(200);

        } else {
            return response()->json(['error' => "Session not found"])->setStatusCode(400);
        }

    }


    function create_session($session)
    {


        try {

            $producer = new RabbitMQService;
            $message = agents::find($session->server)->ipaddress.",8009,".$session->n_packets.",".$session->p_interval.",".$session->w_time.",".$session->dscp.",".agents::find($session->client)->ipaddress.",".$session->p_size.",".$session->id.",".agents::find($session->client)->agent_code;
            // $mesage = "35.229.251.91,8009,20,50,2000,0,192.168.100.52,50,test,./";
          
            $producer->publish($message, "sessions" );
            return "suc";

        } catch (\Exception $e) {
            return "error";
        }



    }

    function report(Request $request)
    {
        //    return response($request->all());
        $sla_breached = false;
        $analytics = new analytics;
        $analytics->session_id = (int) $request['test-name'];
        $analytics->avg_down = (float) $request['avg-downlink-time(ms)'];
        $analytics->avg_jitter = (float) $request['avg-jitter(ms)'];
        $analytics->avg_up = (float) $request['avg-uplink-time(ms)'];
        $analytics->avg_rtt = (float) $request['avg-rtt(ms)'];
        $analytics->max_up = (float) $request['max-uplink-time(ms)'];
        $analytics->max_rtt = (float) $request['max-rtt(ms)'];
        $analytics->max_jitter = (float) $request['max-jitter(ms)'];
        $analytics->max_down = (float) $request['max-downlink-time(ms)'];
        $analytics->min_jitter = (float) $request['min-jitter(ms)'];
        $analytics->min_rtt = (float) $request['min-rtt(ms)'];
        $analytics->min_down = (float) $request['min-downlink-time(ms)'];
        $analytics->min_up = (float) $request['min-uplink-time(ms)'];
        $analytics->r_packets = (int) $request['received-packets'];
        $analytics->st_down = (float) $request['std-dev-downlink-time(ms)'];
        $analytics->st_up = (float) $request['std-dev-uplink-time(ms)'];
        $analytics->st_rtt = (float) $request['td-dev-rtt(ms)'];
        $analytics->t_packets = (int) $request['total-packets'];
        $analytics->save();

        $alerts = alerts::where('session', '=', $analytics->session_id)->get();
        $session = sessions::find($analytics->session_id);
        $profile = profiles::find($session->profile);

        if ($analytics->avg_rtt > $profile->max_rtt || $analytics->avg_jitter > $profile->max_jitter) {
            $sla_breached = true;
        }

        $client = new Client();
        foreach ($alerts as $alert) {
            $url = $alert->endpoint;

            $data = [
                'result' => $analytics,
                'sla_breached' => $sla_breached
            ];
            $jsonData = json_encode($data);

            try {
                $response = $client->post($url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => $jsonData,
                ]);
            } catch (\Exception $e) {
                continue;
            }

        }

        return response("OK");


    }



    public function sessiondetail(Request $request)
    {

        $session = sessions::find($request->sid);

        if ($session) {
            $client = agents::find($session->client);
            if ($client) {
                $server = agents::find($session->server);
                if ($server) {

                    $analytic = analytics::where('session_id', '=', $request->sid)->orderBy('created_at', 'desc')->take(100)->get();
                    $rtt = [];
                    $up = [];
                    $down = [];

                    foreach ($analytic as $lits) {
                        array_push($rtt, ["value"=>$lits->avg_rtt, "date"=>$lits->created_at]);
                        array_push($up, ["value"=>$lits->avg_up, "date"=>$lits->created_at]);
                        array_push($down, ["value"=>$lits->avg_down, "date"=>$lits->created_at]);
                    }


                    $data['client'] = $client;
                    $data['server'] = $server;
                    $data['rtt'] = $rtt;
                    $data['uplink'] = $up;
                    $data['downlink'] = $down;

                    return response()->json(['data' => $data])->setStatusCode(200);

                } else {
                    return response()->json(['error' => "server data not found"])->setStatusCode(400);
                }
            } else {
                return response()->json(['error' => "client data not found"])->setStatusCode(400);
            }
        } else {
            return response()->json(['error' => "session not found"])->setStatusCode(400);
        }






    }



    public function sessionnames(){
        $session = sessions::select('id', 'c_name', 's_name')->get();
        return response()->json($session)->setStatusCode(200);
    }


    public function sessionmetrics(Request $request)
    {

        $session = sessions::find($request->sid);

        if ($session) {
            $client = agents::find($session->client);
            if ($client) {
                $server = agents::find($session->server);
                if ($server) {

                    $analytic = analytics::where('session_id', '=', $request->sid)->get();
                    $rtt = [];
                    $up = [];
                    $down = [];

                    foreach ($analytic as $lits) {
                        array_push($rtt, ["value"=>$lits->avg_rtt, "date"=>$lits->created_at]);
                        array_push($up, ["value"=>$lits->avg_up, "date"=>$lits->created_at]);
                        array_push($down, ["value"=>$lits->avg_down, "date"=>$lits->created_at]);
                    }



                    if($request->metric="uplink"){
                        $data['uplink'] = $up;
                    }
                    elseif($request->metric="rtt"){
                        $data['rtt'] = $rtt;
                    }
                    elseif($request->metric="downlink"){
                        $data['downlink'] = $down;
                    }
                    
                  
                  

                    return response()->json(['data' => $data])->setStatusCode(200);

                } else {
                    return response()->json(['error' => "server data not found"])->setStatusCode(400);
                }
            } else {
                return response()->json(['error' => "client data not found"])->setStatusCode(400);
            }
        } else {
            return response()->json(['error' => "session not found"])->setStatusCode(400);
        }


    }











}