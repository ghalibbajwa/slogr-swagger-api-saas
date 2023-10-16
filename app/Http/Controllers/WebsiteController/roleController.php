<?php

namespace App\Http\Controllers\WebsiteController;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Validator;
use App\Permission;
use App\User;

class roleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $data['roles'] = $roles;
        return response()->json(['data' => $data])->setStatusCode(200);
    }

    public function roleDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rid' => 'required|numeric',
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }




        $role = Role::find($request->rid);

        if ($role) {

            $permissions = $role->permissions;
            $success['role'] = $role;
            $success['permissions'] = $permissions;


            return response()->json(['success' => $success])->setStatusCode(200);
        } else {
            return response()->json(['error' => "Role not found"])->setStatusCode(400);
        }

    }
    public function assignRole(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'uid' => 'required|numeric',
            'rid' => 'required|numeric',
        ]);

        $user = User::find($request->uid);
        $role = Role::find($request->rid);
        if (!$role) {
            return response()->json(['error' => "Role not found"])->setStatusCode(400);
        }

        if (!$user) {
            return response()->json(['error' => "User not found"])->setStatusCode(400);
        }

        $user->roles()->attach($role);

        return response()->json(['success' => ["user" => $user, "role" => $role]])->setStatusCode(200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }




        $role = new Role;

        $role->name = $request->name;

        if (auth()->user()->organization_id != null) {
            $role->organization_id = auth()->user()->organization_id;
        }else{
            return response()->json(['error' => "User does not belong to any Organization"])->setStatusCode(400);
        }



        $role->save();
        $success['role'] = $role;


        return response()->json(['success' => $success])->setStatusCode(200);

    }


    public function edit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'rid' => 'required|numeric',
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }




        $role = Role::find($request->rid);
        if ($role) {
            $role->name = $request->name;

            $role->save();
            $success['role'] = $role;


            return response()->json(['success' => $success])->setStatusCode(200);
        } else {
            return response()->json(['error' => "Role not found"])->setStatusCode(400);
        }



    }

    public function delete(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'rid' => 'required|numeric',
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }




        $role = Role::find($request->rid);
        if ($role) {
            $role->delete();

            return response()->json(['success' => "role deleted"])->setStatusCode(200);
        } else {
            return response()->json(['error' => "role not found"])->setStatusCode(400);

        }



    }
}