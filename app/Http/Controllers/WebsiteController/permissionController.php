<?php

namespace App\Http\Controllers\WebsiteController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\DB;
use Validator;

class permissionController extends Controller
{
    public function index()
    {
        $permission = Permission::all();

        $data['permissions'] = $permission;
        return response()->json(['data' => $data])->setStatusCode(200);
    }

    public function assign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pid.*' => 'required|numeric',
            'rid' => 'required|numeric',
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }



        $trimmedString = trim($request->pid, '{}');
        $arrayValues = explode(',', $trimmedString);

        $permissionarray = array_map('intval', $arrayValues);
        $role = Role::find($request->rid);

        if (!$role) {
            return response()->json(['error' => "Role not found"])->setStatusCode(400);
        }


        $currentPermissions = $role->permissions->pluck('id')->toArray();

        $permissionsToKeep =  array_merge($currentPermissions, $permissionarray);


        $role->permissions()->sync($permissionsToKeep);
        $permissions = $role->permissions;

        $data = [
            'message' => 'Permissions attached to the role successfully',
            'permissions' => $permissions
        ];
        return response()->json($data)->setStatusCode(200);
        ;


    }


    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pid.*' => 'required|numeric',
            'rid' => 'required|numeric'
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()->first()])->setStatusCode(300);
        }


        $trimmedString = trim($request->pid, '{}');
        $arrayValues = explode(',', $trimmedString);

        $permissionarray = array_map('intval', $arrayValues);
        $role = Role::find($request->rid);

        if (!$role) {
            return response()->json(['error' => "Role not found"])->setStatusCode(400);
        }


        $currentPermissions = $role->permissions->pluck('id')->toArray();

        $permissionsToKeep = array_diff($currentPermissions, $permissionarray);


        $role->permissions()->sync($permissionsToKeep);

        $permissions = $role->permissions;
        $data = [
            'message' => 'Permissions removed from the role successfully',
            'permissions' => $permissions
        ];
        return response()->json($data)->setStatusCode(200);
        ;


    }

}