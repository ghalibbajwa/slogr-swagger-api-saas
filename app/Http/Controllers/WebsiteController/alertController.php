<?php

namespace App\Http\Controllers\WebsiteController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\alerts;
use App\sessions;
use App\profiles;



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

    public function index(){

        $alerts  = alerts::all();
        $sessions = sessions::all();
        $profiles = profiles::all();

        $data['alerts'] = $alerts;
        $data['sessions'] = $sessions;
        $data['profiles'] = $profiles;

        return response()->json(['data' => $data])->setStatusCode(200);

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
            'endpoint' => 'required|max:255',
            'session' => 'required|numeric',
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

       $alert->name= $request->name;
       $alert->endpoint = $request->endpoint;
       $alert->session = $request -> profile;



        $alert->save();
        $success['alert'] = $alert;


        return response()->json(['success' => $success])->setStatusCode(200);
    }

}