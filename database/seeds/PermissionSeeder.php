<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\User;
use App\Organization;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Define permissions and associate them with routes
         Permission::create(['name' => 'view_roles']);
         Permission::create(['name' => 'view_role_details']);
         Permission::create(['name' => 'assign_role']);
         Permission::create(['name' => 'add_role']);
         Permission::create(['name' => 'edit_role']);
         Permission::create(['name' => 'delete_role']);
 
         // Permissions for permissionController
         Permission::create(['name' => 'view_permissions']);
         Permission::create(['name' => 'assign_permissions']);
         Permission::create(['name' => 'remove_permissions']);
 
         // Permissions for AuthController
         Permission::create(['name' => 'register']);
 
         // Permissions for other controllers
         Permission::create(['name' => 'view_groups']);
         Permission::create(['name' => 'view_agents']);
         Permission::create(['name' => 'get_cluster']);
         Permission::create(['name' => 'view_alerts']);
         Permission::create(['name' => 'view_data']);
         Permission::create(['name' => 'view_sessions']);
         Permission::create(['name' => 'view_profiles']);
         Permission::create(['name' => 'view_analytics']);
         Permission::create(['name' => 'add_agent']);
         Permission::create(['name' => 'edit_agent']);
         Permission::create(['name' => 'add_session']);
         Permission::create(['name' => 'add_profile']);
         Permission::create(['name' => 'add_alert']);
         Permission::create(['name' => 'delete_agent']);
         Permission::create(['name' => 'delete_session']);
         Permission::create(['name' => 'delete_profile']);
         Permission::create(['name' => 'send_report']);
         Permission::create(['name' => 'register_agent']);
         Permission::create(['name' => 'add_group']);
         Permission::create(['name' => 'get_group']);
         Permission::create(['name' => 'view_organizations']);
         Permission::create(['name' => 'add_organization']);
         Permission::create(['name' => 'edit_organization']);
         Permission::create(['name' => 'delete_organization']);
         Permission::create(['name' => 'assign_organization']);
       
        $organization=Organization::Create([
            'name' => 'slogr',
            "address" => 'middle of nowhere',
            "phone" => "12345132"
        ]);



        $role = Role::Create([
            'name' => 'superadmin',
            "organization_id" => $organization->id
        ]);

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@slogr.io',
            'password' => Hash::make('password'),
            "organization_id" => $organization->id
        ]);

        $allPermissions = Permission::all();
        $role->permissions()->sync($allPermissions);

        $user->roles()->attach($role);

    }
}
