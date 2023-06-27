<?php

namespace App\Http\Controllers\WebsiteController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\agents;
use App\groups;
use App\sessions;
use App\profiles;


class groupControlller extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/groups",
     *     summary="Get data for Groups",
     *     description="Get data for agents, sessions, profiles, and groups",
     *     tags={"Groups"},
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
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="content", type="string"),
     *                         @OA\Property(property="classses", type="array", @OA\Items(type="string"))
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="sessions",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="content", type="string"),
     *                         @OA\Property(property="classses", type="array", @OA\Items(type="string"))
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="profile",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="content", type="string"),
     *                         @OA\Property(property="classses", type="array", @OA\Items(type="string"))
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="groups",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="content", type="string"),
     *                         @OA\Property(property="classses", type="array", @OA\Items(type="string"))
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    function index()
    {
        $agents = agents::all();
        $sessions = sessions::all();
        $profiles = profiles::all();
        $groups = groups::all();


        $agent = array();
        $count = 0;
        foreach ($agents as $ag) {
            $agent[$count] = [
                'id' => $ag->id,
                'content' => $ag->name,
                'classses' => ["dd-nochildren"],
            ];

            $count += 1;

        }
        $session = array();
        $count = 0;
        foreach ($sessions as $se) {
            $session[$count] = [
                'id' => $se->id,
                'content' => $se->s_name . ' to ' . $se->c_name,
                'classses' => ["dd-nochildren"],
            ];

            $count += 1;

        }
        $profile = array();
        $count = 0;
        foreach ($profiles as $pr) {
            $profile[$count] = [
                'id' => $pr->id,
                'content' => $pr->name,
                'classses' => ["dd-nochildren"],
            ];

            $count += 1;

        }

        $data['agents'] = $agent;
        $data['sessions'] = $session;
        $data['profile'] = $profile;
        $data['groups'] = $groups;
        return response()->json(['data' => $data])->setStatusCode(200);

    }

    /**
     * @OA\Post(
     *     path="/api/add-group",
     *     summary="Store data",
     *     description="Store agent, session, and profile data",
     *     tags={"Groups"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data to be stored",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="agent",
     *                 type="array",
     *                 @OA\Items(type="string")
     *             ),
     *             @OA\Property(
     *                 property="session",
     *                 type="array",
     *                 @OA\Items(type="string")
     *             ),
     *             @OA\Property(
     *                 property="profile",
     *                 type="array",
     *                 @OA\Items(type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="object",
     *                 @OA\Property(
     *                     property="agents",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="sessions",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 ),
     *                 @OA\Property(
     *                     property="profiles",
     *                     type="array",
     *                     @OA\Items(type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */


    function store(Request $request)
    {

        $agent = array();
        $session = array();
        $profile = array();
        $data = $request->all();
        foreach ($data as $key => $value) {
            $name = explode('|', $key);
            if ($name[0] == 'agent') {
                array_push($agent, $value);

            } elseif ($name[0] == 'session') {
                array_push($session, $value);
            } elseif ($name[0] == 'profile') {
                array_push($profile, $value);
            }
        }
        if (empty($request->g_id)) {
            $group = new groups;
        } else {
            $group = groups::find($request->g_id);
        }
        $group->name = $request->name;
        $group->save();

        $group->agents()->attach($agent);
        $group->sessions()->attach($session);
        $group->profiles()->attach($profile);

        $success['agents'] = $group->agents;
        $success['sessions'] = $group->sessions;
        $success['profiles'] = $group->profiles;


        return response()->json(['success' => $success])->setStatusCode(200);



    }

    /**
     * @OA\Post(
     *     path="/api/get-group",
     *     summary="Get group data",
     *     description="Retrieve data for a specific group",
     *     tags={"Groups"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Group ID",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="id",
     *                 type="integer"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="object",
     *                 @OA\Property(
     *                     property="group",
     *                     type="object"
     *                 ),
     *                 @OA\Property(
     *                     property="grouped",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="content", type="string"),
     *                         @OA\Property(property="classses", type="array", @OA\Items(type="string"))
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    function getdata(Request $request)
    {

        $group = groups::find($request->id);
        $agents = $group->agents;
        $sessions = $group->sessions;
        $profiles = $group->profiles;

        $agent = array();
        $count = 0;
        foreach ($agents as $ag) {
            $agent[$count] = [
                'id' => $ag->id,
                'content' => $ag->name,
                'classses' => ["dd-nochildren"],
            ];

            $count += 1;

        }
        $session = array();
        $count = 0;
        foreach ($sessions as $se) {
            $session[$count] = [
                'id' => $se->id,
                'content' => $se->s_name . ' to ' . $se->c_name,
                'classses' => ["dd-nochildren"],
            ];

            $count += 1;

        }
        $profile = array();
        $count = 0;
        foreach ($profiles as $pr) {
            $profile[$count] = [
                'id' => $pr->id,
                'content' => $pr->name,
                'classses' => ["dd-nochildren"],
            ];

            $count += 1;

        }
        $grouped = array_merge($agent, $session, $profile);
        $success['group'] = $group;
        $success['grouped'] = $grouped;


        return response()->json(['success' => $success])->setStatusCode(200);

    }
}