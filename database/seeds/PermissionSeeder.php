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
        $permissions = [
            'sessiondetail',
            'sessionmetrics',
            'view_analytics',
            'referenceSessions',
            'view_organizations',
            'add_organization',
            'assign_organization',
            'edit_organization',
            'delete_organization',
            'view_roles',
            'view_role_details',
            'assign_role',
            'add_role',
            'edit_role',
            'delete_role',
            'view_permissions',
            'assign_permissions',
            'remove_permissions',
            'register',
            'view_groups',
            'view_agents',
            'get_cluster',
            'view_alerts',
            'view_links',
            'view_agentlinks',
            'view_sessions',
            'view_profiles',
            'add_agent',
            'edit_agent',
            'add_session',
            'add_profile',
            'add_alert',
            'delete_agent',
            'delete_session',
            'delete_profile',
            'send_report',
            'register_agent',
            'add_group',
            'delete_group',
            'edit_group',
            'get_group',
            'sessionnames',
            'edit_alert',
            'delete_alert'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
       
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
