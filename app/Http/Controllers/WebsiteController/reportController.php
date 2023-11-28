<?php

namespace App\Http\Controllers\WebsiteController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\alerts;
use App\reports;
class reportController extends Controller
{
    function index()
    {

        $reports = reports::all();

        return response()->json($reports)->setStatusCode(200);

    }
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'alerts.*' => 'required|numeric',
            'name' => 'required|max:255',
            'email' => 'required|email', 
            'time' => 'required|date_format:H:i', 
        
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(400);
        }


        $alerts = array_map('intval', $request->alerts);
        $report = new reports();
        $report->name = $request->name;
        $report->time = $request->time;
        $report->email = $request->email;
        $report->save();

        $report->alerts()->sync($alerts);
        $data = [
            'message' => 'Alerts attached to the report successfully',
            'report' => $report
        ];

        return response()->json($data)->setStatusCode(200);
    }


    function getdata($id)
    {

        $report = reports::find($id);
        if ($report) {
          
            $data = [
                'report' => $report,
                'alerts' =>$report->alerts
            ];


            return response()->json($data)->setStatusCode(200);

        } else {
            return response()->json(['error' => "report not found"])->setStatusCode(400);

        }





    }


    public function remove(Request $request)
    {


        $report = reports::find($request->id);

        if ($report) {
            $report->delete();

            return response()->json(['success' => "report deleted"])->setStatusCode(200);
        } else {
            return response()->json(['error' => "report not found"])->setStatusCode(400);

        }

    }


    function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alerts.*' => 'required|numeric',
            'name' => 'max:255',
            'email' => 'email', 
            'time' => 'date_format:H:i', 
            'id' => 'required|numeric',
        
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(400);
        }

       

        $alertsarray = array_map('intval', $request->alerts);


        $report = reports::find($request->id);

        if($report){

            if($request->name){
                $report->name = $request->name;
            }

            if($request->email){
                $report->email = $request->email;
            }


            if($request->time){
                $report->time = $request->time;
            }
            $report->save();

            $report->alerts()->sync($alertsarray);


        }

       

        $data = [
            'message' => 'alerts updated to the report successfully',
            'report' => $report
        ];




        return response()->json($data)->setStatusCode(200);



    }

}
