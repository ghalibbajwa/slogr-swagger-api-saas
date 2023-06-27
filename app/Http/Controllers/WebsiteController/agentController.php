<?php

namespace App\Http\Controllers\WebsiteController;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\agents;
use Illuminate\Support\Facades\Response;
use Validator;
use App\asn_table;


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
    public function index()
    {
        $agents = agents::all();
        $data['agents'] = $agents;
        return response()->json(['data' => $data])->setStatusCode(200);


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

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'edit' => 'required|boolean',
                'ip' => 'required|ip',
                'name' => 'required|max:255',
            ]);



            // dd($validator->fails()); 

            if ($validator->fails()) {

                return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
            }


            if ($request->edit == true) {
                $agent = agents::find($request->aid);
            } else {

                $agent = new agents;
            }
            $agent->name = $request->name;
            $agent->ipaddress = $request->ip;







            $agent->save();
            $success['status'] = 'Agent Saved';
            $success['agent'] = $agent;


            return response()->json(['success' => $success])->setStatusCode(200);

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
        $agent->delete();

        return response()->json(['success' => "agent deleted"])->setStatusCode(200);

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
}