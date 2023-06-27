<?php

namespace App\Http\Controllers\WebsiteController;

use App\agents;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\profiles;


class profileController extends Controller
{
    /**
 * @OA\Get(
 *     path="/api/index",
 *     summary="Get profiles",
 *     description="Retrieve all profiles",
 *     tags={"Profiles"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(
 *                     property="profiles",
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
        $profiles = profiles::all();
        $data['profiles']=$profiles;
        return response()->json(['data' => $data])->setStatusCode(200);


    }


    /**
 * @OA\Post(
 *     path="/api/add-profile",
 *     summary="Create a profile",
 *     description="Create a new profile",
 *     tags={"Profiles"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 description="Profile name",
 *                 example="Profile 1"
 *             ),
 *             @OA\Property(
 *                 property="p_size",
 *                 type="integer",
 *                 description="Packet size",
 *                 example="1500"
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
 *                 property="max_loss",
 *                 type="integer",
 *                 description="Maximum loss",
 *                 example="5"
 *             ),
 *             @OA\Property(
 *                 property="max_down",
 *                 type="integer",
 *                 description="Maximum downlink time",
 *                 example="50"
 *             ),
 *             @OA\Property(
 *                 property="max_up",
 *                 type="integer",
 *                 description="Maximum uplink time",
 *                 example="50"
 *             ),
 *             @OA\Property(
 *                 property="max_jitter",
 *                 type="integer",
 *                 description="Maximum jitter",
 *                 example="10"
 *             ),
 *             @OA\Property(
 *                 property="max_rtt",
 *                 type="integer",
 *                 description="Maximum RTT",
 *                 example="100"
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
 *                 description="Profile ID (required if edit flag is true)",
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
 *                     property="profile",
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=300,
 *         description="Validation error",
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
            'name' => 'required|max:255',
            'p_size' => 'required|numeric',
            'count' => 'required|numeric',
            'n_packets' => 'required|numeric',
            'p_interval' => 'required|numeric',
            'w_time' => 'required|numeric',
            'dscp' => 'required|numeric',
            'max_loss' => 'required|numeric',
            'max_down' => 'required|numeric',
            'max_up' => 'required|numeric',
            'max_jitter' => 'required|numeric',
            'max_rtt' => 'required|numeric'
        ]);



        // dd($validator->fails()); 
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }

        if ($request->edit == true) {
            $profile = profiles::find($request->aid);
        } else {

            $profile = new profiles;
        }

        $profile->name = $request->name;
        $profile->count = $request->serve;
        $profile->count = $request->count;
        $profile->n_packets = $request->n_packets;
        $profile->p_interval = $request->p_interval;
        $profile->w_time = $request->w_time;
        $profile->dscp = $request->dscp;
        $profile->max_loss = $request->max_loss;
        $profile->max_uplink = $request->max_up;
        $profile->p_size = $request->p_size;
        $profile->max_downlink = $request->max_down;
        $profile->max_rtt = $request->max_rtt;
        $profile->max_jitter = $request->max_jitter;


        $profile->save();
        $success['profile'] = $profile;
            

        return response()->json(['success' => $success])->setStatusCode(200);
    }


    /**
 * @OA\Post(
 *     path="/api/delete-profile",
 *     summary="Delete a profile",
 *     description="Delete a profile by ID",
 *     tags={"Profiles"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Profile ID",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="delete",
 *                 type="integer",
 *                 description="ID of the profile to be deleted"
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



        $agent = profiles::find($request->delete);
        $agent->delete();
        return redirect()->back()->with('a_status', 'success');


    }

    /**
     * @OA\Get(
     *     path="/api/push/{id}",
     *     summary="Push profile to clients",
     *     description="Push the specified profile to clients",
     *     tags={"Profiles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Profile ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function push($id)
    {
        $profile = profiles::find($id);
        $agents = agents::all();
        $client = new \GuzzleHttp\Client();
        foreach ($agents as $ag) {
            if ($ag->type == "client" and $ag->os == "win") {
                $url = $ag->ipaddress . ":5000/create-profile";
                $data = [
                    'name' => $profile->name,
                    'n_packets' => $profile->n_packets,
                    'intervel' => $profile->p_interval,
                    'p_size' => $profile->p_size,
                    'w_time' => $profile->w_time,
                    'dscp' => $profile->dscp,
                    'max_loss' => $profile->max_loss,
                    'max_up' => $profile->max_uplink,
                    'max_down' => $profile->max_downlink,
                    'max_rtt' => $profile->max_rtt,
                    'max_jitter' => $profile->max_jitter,
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
        }

        return response()->json(['success' => 'success'])->setStatusCode(200);




    }
}