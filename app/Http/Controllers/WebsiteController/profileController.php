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
     *     path="/api/profiles",
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
        $data['profiles'] = $profiles;
        return response()->json($data)->setStatusCode(200);


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
     *                 property="p-size",
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
     *                 property="n-packets",
     *                 type="integer",
     *                 description="Number of packets",
     *                 example="100"
     *             ),
     *             @OA\Property(
     *                 property="p-interval",
     *                 type="integer",
     *                 description="Packet interval",
     *                 example="10"
     *             ),
     *             @OA\Property(
     *                 property="w-time",
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
     *                 property="max-loss",
     *                 type="integer",
     *                 description="Maximum loss",
     *                 example="5"
     *             ),
     *             @OA\Property(
     *                 property="max-down",
     *                 type="integer",
     *                 description="Maximum downlink time",
     *                 example="50"
     *             ),
     *             @OA\Property(
     *                 property="max-up",
     *                 type="integer",
     *                 description="Maximum uplink time",
     *                 example="50"
     *             ),
     *             @OA\Property(
     *                 property="max-jitter",
     *                 type="integer",
     *                 description="Maximum jitter",
     *                 example="10"
     *             ),
     *             @OA\Property(
     *                 property="max-rtt",
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
            'rtt_g' => 'required|numeric',
            'rtt_r' => 'required|numeric',
            'uplink_g' => 'required|numeric',
            'uplink_r' => 'required|numeric',
            'downlink_g' => 'required|numeric',
            'downlink_r' => 'required|numeric',
            'delay_g' => 'required|numeric',
            'delay_r' => 'required|numeric',
            'downlink_bw_g' => 'required|numeric',
            'downlink_bw_r' => 'required|numeric',
            'uplink_bw_g' => 'required|numeric',
            'uplink_bw_r' => 'required|numeric',
            'jitter_g' => 'required|numeric',
            'jitter_r' => 'required|numeric',
            'loss_g' => 'required|numeric',
            'loss_r' => 'required|numeric',
        ]);


        // dd($validator->fails()); 
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(400);
        }

        if ($request->edit == true) {
            $profile = profiles::find($request->id);
            if(!$profile){
                return response()->json(['error' => "profile not found"] )->setStatusCode(400);
            }
        } else {

            $profile = new profiles;
        }

        $fields = [
            'name', 'count', 'n_packets', 'p_interval', 'w_time', 'dscp','p_size',
            'rtt-g', 'rtt-r', 'uplink-g', 'uplink-r', 'downlink-g', 'downlink-r',
            'delay-g', 'delay-r', 'downlink-bw-g', 'downlink-bw-r', 'uplink-bw-g', 'uplink-bw-r',
            'jitter-g', 'jitter-r', 'loss-g', 'loss-r'
        ];

        $fields2 = [
            'name', 'count', 'n_packets', 'p_interval', 'w_time', 'dscp','p_size',
            'rtt_g', 'rtt_r', 'uplink_g', 'uplink_r', 'downlink_g', 'downlink_r',
            'delay_g', 'delay_r', 'downlink_bw_g', 'downlink_bw_r', 'uplink_bw_g', 'uplink_bw_r',
            'jitter_g', 'jitter_r', 'loss_g', 'loss_r'
        ];

        for ($i = 0; $i < count($fields); $i++) {
            $profile->{$fields[$i]} = $request->{$fields2[$i]};
        }

       


        $profile->save();
        $success['profile'] = $profile;


        return response()->json($success)->setStatusCode(200);
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



        $profile = profiles::find($request->id);

        if ($profile) {
            $profile->delete();

            return response()->json(['success' => "profile deleted"])->setStatusCode(200);
        } else {
            return response()->json(['error' => "profile not found"])->setStatusCode(400);

        }


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
                    'n-packets' => $profile->n-packets,
                    'intervel' => $profile->p-interval,
                    'p-size' => $profile->p-size,
                    'w-time' => $profile->w-time,
                    'dscp' => $profile->dscp,
                    'max-loss' => $profile->max-loss,
                    'max-up' => $profile->max-uplink,
                    'max-down' => $profile->max-downlink,
                    'max-rtt' => $profile->max-rtt,
                    'max-jitter' => $profile->max-jitter,
                ];
                $jsonData = json-encode($data);

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