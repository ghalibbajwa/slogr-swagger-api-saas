<?php

namespace App\Http\Controllers\WebsiteController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organization;
use Validator;
use App\User;

class organizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();
        $data['organizations'] = $organizations;
        return response()->json(['data' => $data])->setStatusCode(200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',

        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }


        $userOrganizationId = auth()->user()->organization_id;
        
        if($userOrganizationId == null || empty($userOrganizationId)){

      
        $organizations = new Organization;

        $organizations->name = $request->name;
        $organizations->address = $request->address;
        $organizations->phone = $request->phone;
        $organizations->save();

        $user = User::find(auth()->user()->id);
        $user->organization_id = $organizations->id;
        $user->save();
        $success['organizations'] = $organizations;


        return response()->json(['success' => $success])->setStatusCode(200);
    }else{
        return response()->json(['error' => "you are already a part of the organization"])->setStatusCode(300);
    }

    }



    public function assign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(400);
        }





        $user = User::where('email','=',$request->email)->first();

        $organization = Organization::find(auth()->user()->organization_id);

        if (!$user) {
            return response()->json(['error' => "User not found"])->setStatusCode(400);
        }
        if (!$organization) {
            return response()->json(['error' => "Organization not found"])->setStatusCode(400);
        }

        $user->organization_id = $organization->id;
        $user->save();



        $data = [
            'message' => $user->name . " added to " . $organization->name,
            'User' => $user
        ];
        return response()->json($data)->setStatusCode(200);
        ;


    }


    public function delete(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'oid' => 'required|numeric',
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }




        $organization = Organization::find($request->oid);
        if ($organization) {
            $organization->delete();

            return response()->json(['success' => "Organization deleted"])->setStatusCode(200);
        } else {
            return response()->json(['error' => "Organization not found"])->setStatusCode(400);

        }



    }


    public function edit(Request $request)
    {
        
        $organization = Organization::find(auth()->user()->organization_id);
        if ($organization) {
            if($request->name){
                $organization->name = $request->name;
            }
            if($request->address){
                $organization->address = $request->address;
            }
            if($request->phone){
                $organization->phone = $request->phone;
            }



            $organization->save();
            $success['organization'] = $organization;


            return response()->json(['success' => $success])->setStatusCode(200);
        } else {
            return response()->json(['error' => "Organization not found"])->setStatusCode(400);
        }



    }
}