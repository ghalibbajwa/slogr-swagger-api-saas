<?php

namespace App\Http\Controllers\WebsiteController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\analytics;
use App\sessions;
use App\profiles;
use App\agents;
use App\groups;
use App\alerts;


class dataController extends Controller
{
    
    /**
     * @OA\Get(
     *     path="/api/data",
     *     summary="Get data",
     *     description="Retrieve all available data",
     *     tags={"Data Downloading and Reporting"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="analytics",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="sessions",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="agents",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="profiles",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="alerts",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *                 @OA\Property(
     *                     property="groups",
     *                     type="array",
     *                     @OA\Items()
     *                 ),
     *             )
     *         )
     *     )
     * )
     */
    public function index(){
        $analytics = analytics::all();
        $sessions  = sessions::all();
        $profiles = profiles::all();
        $groups = groups::all();
        $alerts = alerts::all();
        $agents = agents::all();

        $data['analytics'] = $analytics;
        $data['sessions'] = $sessions;
        $data['profiles'] = $profiles;
        $data['groups'] = $groups;
        $data['alerts'] = $alerts;
        $data['agents'] = $agents;

        return response()->json(['data' => $data])->setStatusCode(200);


    }
}
