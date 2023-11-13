<?php

namespace App\Http\Controllers\WebsiteController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\agents;
use App\groups;
use App\sessions;
use App\profiles;
use Validator;


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


        $groups = groups::all();
        




        return response()->json($groups)->setStatusCode(200);

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

        $validator = Validator::make($request->all(), [
            'sessions.*' => 'required|numeric',
            'name' => 'required|max:255',
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }

        $trimmedString = trim($request->sessions, '{}');
        $arrayValues = explode(',', $trimmedString);

        $sessionsarray = array_map('intval', $arrayValues);


        $group = new groups;

        $group->name = $request->name;

        $group->save();

        $group->sessions()->sync($sessionsarray);

        $sessions = $group->sessions;

        $data = [
            'message' => 'Sessions attached to the Group successfully',
            'group' => $group
        ];




        return response()->json($data)->setStatusCode(200);



    }





    public function remove(Request $request){

        
        $group = groups::find($request->id);

        if ($group) {
            $group->delete();

            return response()->json(['success' => "group deleted"])->setStatusCode(200);
        } else {
            return response()->json(['error' => "group not found"])->setStatusCode(400);

        }

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
        
        $sessions = $group->sessions;

        $data = [
            'group' => $group,
        ];




        return response()->json($data)->setStatusCode(200);



    }
}