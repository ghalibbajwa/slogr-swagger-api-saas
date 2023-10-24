<?php

namespace App\Http\Controllers\WebsiteController;

use App\agents;
use App\analytics;
use App\profiles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\sessions;
use App\groups;
use Validator;

class homeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/map",
     *     summary="Get Map Data",
     *     description="Retrieve data relevant to map including pointers, lines etc",
     *     tags={"Map"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="sessions",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="pointers",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="groups",
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
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="sdata",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="glinks",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="gpros",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="poins",
     *                     type="array",
     *                     @OA\Items()
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    function index()
    {
        $sessions = sessions::all();
        $agents = agents::all();
        $groups = groups::all();
        $profiles = profiles::all();
        $glinks = array();
        $count = 0;
        foreach ($groups as $gs) {

            $temp_s = [];

            foreach ($gs->sessions as $se) {
                array_push($temp_s, $se->id);
            }
            $glinks[$gs->id] = $temp_s;
            $count += 1;

        }


        $gpros = array();
        $count = 0;
        foreach ($groups as $gp) {

            $temp_p = [];
            foreach ($gp->profiles as $sp) {
                array_push($temp_p, $sp->id);
            }
            $gpros[$gp->id] = $temp_p;
            $count += 1;

        }


        $pointers = array();
        $count = 0;
        foreach ($agents as $ae) {
            $pointers[$count] = [
                'name' => $ae->name,
                'lat' => $ae->lat,
                'long' => $ae->long,
                'd' => $ae->ipaddress,
            ];

            $count += 1;

        }

        $links = array();
        $count = 0;
        foreach ($sessions as $se) {
            $server = agents::find($se->server);
            $client = agents::find($se->client);
            $links[$se->id] = [
                'server' => $se->server,
                'client' => $se->client,
                'points' => [$server->lat, $server->long, $client->lat, $client->long],
                'stroke' => 'blue',
                'd' => $server->name . ' -> ' . $client->name
            ];

            $count += 1;
        }

        $pros = [];

        foreach ($profiles as $pro) {
            $pros[$pro->id] = $pro;
        }


        $poins = [];

        foreach ($agents as $ae) {
            $poins[$ae->id] = [
                'name' => $ae->name,
                'lat' => $ae->lat,
                'long' => $ae->long,
                'd' => $ae->ipaddress,
            ];
        }

        $sdata = [];
        foreach ($sessions as $ses) {
            $cd = analytics::where('session_id', '=', $ses->id)->first();
            $sdata[$ses->id] = $cd;
        }


        $data['sessions'] = $sessions;
        $data['pointers'] = $pointers;
        $data['links'] = $links;
        $data['groups'] = $groups;
        $data['profiles'] = $profiles;
        $data['pros'] = $pros;
        $data['sdata'] = $sdata;
        $data['glinks'] = $glinks;
        $data['gpros'] = $gpros;
        $data['poins'] = $poins;
        return response()->json(['data' => $data])->setStatusCode(200);

    }


    function agentLinks(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'aid' => 'required|numeric',
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }

        $sessions = sessions::where('server', '=', $request->aid)->get();
        
        $agents = agents::all();
        $agents = collect($agents)->pluck(null, 'id')->all();
        $links = array();
        $count = 0;
        foreach ($sessions as $se) {
            try {
                $server = $agents[$se->server];
                $client = $agents[$se->client];
                $links[$count] = [
                    'coordinates' => [[floatval($server->long), floatval($server->lat)], [floatval($client->long), floatval($client->lat)]],
                    'color' => 'blue',
                    'session_id' => $se->id
                ];

                $count += 1;
            } catch (\Exception $e) {
                continue;
            }
        }
        return response()->json($links);



    }




    function getLinks()
    {
        $sessions = sessions::all();
        $agents = agents::all();
        $agents = collect($agents)->pluck(null, 'id')->all();


        $links = array();
        $count = 0;
        foreach ($sessions as $se) {
            try {
                $server = $agents[$se->server];
                $client = $agents[$se->client];
                $links[$count] = [
                    'coordinates' => [[floatval($server->long), floatval($server->lat)], [floatval($client->long), floatval($client->lat)]],
                    'color' => 'blue',
                    'session_id' => $se->id
                ];

                $count += 1;
            } catch (\Exception $e) {
                continue;
            }
        }


        return response()->json($links);
    }

    function getCluster()
    {
        $agents = agents::all();


        $featureCollection = [
            "type" => "FeatureCollection",
            "crs" => [
                "type" => "name",
                "properties" => [
                    "name" => "urn:ogc:def:crs:OGC:1.3:CRS84",
                ],
            ],
            "features" => [],
        ];

        // Iterate through your data and add it to the features array
        foreach ($agents as $item) {
            $feature = [
                "type" => "Feature",
                "properties" => [
                    "id" => $item['id'],
                    "name" => $item['name'],
                    "ipaddress" => $item['ipaddress'],
                    "location" => $item['location'],
                    "platform" => $item['platform'],
                    "organization" => $item['Organization']

                    // Add more properties here based on your data
                ],
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => [$item['long'], $item['lat']],
                ],
            ];

            $featureCollection['features'][] = $feature;
        }


        return response()->json($featureCollection)->setStatusCode(200);

    }
}