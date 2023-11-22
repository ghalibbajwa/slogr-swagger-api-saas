<?php

namespace App\Http\Controllers\WebsiteController;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\agents;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Validator;
use App\asn_table;
use App\Services\RabbitMQService;
use Illuminate\Support\Facades\Crypt;
use App\Organization;
use App\sessions;


class agentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/agents",
     *     summary="Get agents",
     *     description="Get all agents",
     *     tags={"Agents"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="agents",
     *                     type="array",
     *                     @OA\Items(type="object")
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
        $agents = agents::orderBy('created_at', 'desc')->get();

        if ($request->name) {
            $name = $request->name;
            $filteredAgents = $agents->filter(function ($agent) use ($name) {
                return Str::contains($agent->name, $name);
            });
            $agents = $filteredAgents;
        }


        if ($request->country) {
            $country = $request->country;
            $filteredAgents = $agents->filter(function ($agent) use ($country) {
                return Str::contains($agent->Country, $country);
            });
            $agents = $filteredAgents;

        }

        if ($request->organization) {
            $organization = $request->organization;
            $filteredAgents = $agents->filter(function ($agent) use ($organization) {
                return Str::contains($agent->Organization, $organization);
            });
            $agents = $filteredAgents;
        }

        if ($request->city) {
            $city = $request->city;
            $filteredAgents = $agents->filter(function ($agent) use ($city) {
                return Str::contains($agent->location, $city);
            });
            $agents = $filteredAgents;
        }



        if ($request->ipaddress) {
            $ipaddress = $request->ipaddress;
            $ips = explode('-', $ipaddress);

            $agentsBetweenIPs = [];

            foreach ($agents as $agent) {
                $agentIpAddress = $agent->ipaddress;

                // Convert IP addresses to numerical representations for comparison
                $startIpNumeric = ip2long($ips[0]);
                $endIpNumeric = ip2long($ips[1]);
                $agentIpNumeric = ip2long($agentIpAddress);

                if ($agentIpNumeric >= $startIpNumeric && $agentIpNumeric <= $endIpNumeric) {
                    $agentsBetweenIPs[] = $agent;
                }
            }
            $agentsArray = $agentsBetweenIPs;
        } else {
            $agentsArray = $agents->toArray();
        }

        $offset = ($page - 1) * $size;
        $agentsForPage = array_slice($agentsArray, $offset, $size);


        $data['agentOS'] = ['windows', 'linux'];
        $data['count'] = count($agentsArray);
        $data['next'] = $next;
        $data['prev'] = $prev;
        $data['page-size'] = $size;
        $data['agents'] = $agentsForPage;

        return response()->json(['data' => $data])->setStatusCode(200);


    }


    public function generatescript($id)
    {
        $agent = agents::find($id);

    }



    public function mqtest()
    {
        $producer = new RabbitMQService;
        $mesage = "35.229.251.91,8009,20,50,2000,0,192.168.100.52,50,test,./";
        $producer->publish($mesage, "hello");
    }




    public function getDownload($id)
    {
        // dd('here');
        $agent = agents::find($id);
        // dd($agent->os);

        if ($agent->type == "server") {
            $file = public_path() . "/downloads/server/docker-compose.yml";
            $headers = array(
                'Content-Type: application/pdf',
            );

            return Response::download($file, 'docker-compose.yml', $headers);
        } elseif ($agent->type == "client") {
            if ($agent->os == "win") {

                $file = public_path() . "/downloads/client/win/installer-slogr.exe";
                $headers = array(
                    'Content-Type: application/pdf',
                );

                return Response::download($file, 'installer-slogr.exe', $headers);

            } else {
                $file = public_path() . "/downloads/client/linux/docker-compose.yml";
                $headers = array(
                    'Content-Type: application/pdf',
                );

                return Response::download($file, 'docker-compose.yml', $headers);
            }

        }



        //PDF file is stored under project/public/download/info.pdf



    }
    /**
     * @OA\Post(
     *     path="/api/edit-agent",
     *     summary="Edit agent",
     *     description="update an existing agent",
     *     tags={"Agents"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"edit", "name", "ip"},
     *                 @OA\Property(
     *                     property="edit",
     *                     type="boolean",
     *                     description="Flag indicating whether to edit an existing agent (true) or create a new agent (false)"
     *                 ),
     *                 @OA\Property(
     *                     property="aid",
     *                     type="integer",
     *                     description="ID of the agent to edit (required when 'edit' is true)"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name of the agent"
     *                 ),
     *                 @OA\Property(
     *                     property="ip",
     *                     type="string",
     *                     description="IP address of the agent"
     *                 ),
     *               
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agent stored successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     ),
     * security={{"passport": {}}},
     *     @OA\Header(
     *         header="Accept",
     *         description="Accept header",
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     )
     *  
     * )
     */

    public function edit_agent(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [

                'name' => 'required|max:255',
                'aid' => 'required|numeric',
            ]);



            // dd($validator->fails()); 

            if ($validator->fails()) {

                return response()->json(['error' => $validator->errors()->first()])->setStatusCode(400);
            }



            $agent = agents::find($request->aid);


            if ($agent) {
                $agent->name = $request->name;

                $agent->save();
                $success['status'] = 'Agent Saved';
                $success['agent'] = $agent;


                return response()->json(['success' => $success])->setStatusCode(200);

            } else {
                return response()->json(['error' => "Agent not found"])->setStatusCode(400);

            }


        } catch (\Exception $e) {
            return $e;
        }
    }


    /**
     * @OA\Post(
     *     path="/api/delete-agent",
     *     summary="Delete an agent",
     *     description="Delete an agent by ID",
     *     tags={"Agents"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Agent ID",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="delete",
     *                 type="integer",
     *                 description="The ID of the agent to be deleted"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agent deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="string",
     *                 description="Message indicating successful deletion"
     *             )
     *         )
     *     )
     * )
     */

    public function delete(Request $request)
    {

        $agent = agents::find($request->delete);

        if ($agent) {
            $agent->delete();

            return response()->json(['success' => "agent deleted"])->setStatusCode(200);
        } else {
            return response()->json(['error' => "Agent not found"])->setStatusCode(400);

        }

    }



    public function get_ip()
    {

        $ipAddress = '8.8.8.8';
        $ans = "";
        // dd(ip2long($ipAddress));
        $row = asn_table::all();
        // $rows = asn_table::whereRaw("INET('$ipAddress') << startip AND INET('$ipAddress') >> endip")->get();
        foreach ($row as $r) {
            if (ip2long($r->endip) >= ip2long($ipAddress) && ip2long($r->startip) <= ip2long($ipAddress)) {
                $ans = $r->isp;
                break;
            }
        }
        dd($ans);

    }


    public function add_agent(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'name' => 'required|max:255',
            'agent_code' => 'required|max:225'
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(400);
        }

        $agent = agents::where('agent_code', "=", $request->agent_code)->first();

        if ($agent) {
            $agent->name = $request->name;

            $agent->status = "active";
            $agent->organization_id = auth()->user()->organization_id;
            $agent->Organization = Organization::find(auth()->user()->organization_id)->name;
            $agent->save();
            return response()->json([$agent])->setStatusCode(200);

        } else {
            return response()->json(['error' => "No Agent Found, check your agent code"])->setStatusCode(400);
        }



    }

    public function register(Request $request)
    {



        if ($request->countryname == null) {
            $data = \Location::get($request->ipaddress);
        } else {
            $data = new Request();
            $cords = explode(',', $request->country);
            $data->replace(['latitude' => $cords[0], 'longitude' => $cords[1], 'cityName' => $request->countryname]);


        }


        $agent = new agents;

        $agent->name = $request->machine_name;
        $agent->ipaddress = $request->ipaddress;
        $agent->platform = $request->platform;
        $agent->lat = $data->latitude;
        $agent->long = $data->longitude;
        $agent->location = $data->cityName;
        $agent->machine_name = $request->machine_name;
        $agent->arch = $request->arch[0];
        $agent->processor = $request->processor;
        $agent->machine = $request->machine;



        $agent->save();

        return response($request->all());

    }


    public function referenceSessions(Request $request)
    {
        $sessions = sessions::where('server', '=', $request->aid)->select('client', 'id')->get();


        $agents = [];
        foreach ($sessions as $session) {
            try {
                $curr = agents::find($session->client);
                $curr->session_id = $session->id;
                array_push($agents, $curr);
            } catch (Exception $e) {
                continue;
            }
        }


        return response()->json(['sessions' => $agents])->setStatusCode(200);

    }
}