<?php

use Illuminate\Database\Seeder;
use App\profiles;
use App\User;
use App\Role;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {




        Role::Create([
            'name' => 'admin'
        ]);
        Role::Create([
            'name' => 'guest'
        ]);
        Role::Create([
            'name' => 'moderator'
        ]);

        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@slogr.io',
            'password' => Hash::make('password'),
        ]);

        profiles::create([
            'name' => 'voip',
            'count' => 1,
            'n_packets' => 100,
            'p_interval' => 40,
            'p_size' => 50,
            'w_time' => 2000,
            'dscp' => 46,
            'max_loss' => 1,
            'max_uplink' => 100,
            'max_downlink' => 100,
            'max_jitter' => 30,
            'max_rtt' => 150,
        ]);
        profiles::create([
            'name' => 'gaming',
            'count' => 1,
            'n_packets' => 100,
            'p_interval' => 40,
            'p_size' => 50,
            'w_time' => 2000,
            'dscp' => 26,
            'max_loss' => 5,
            'max_uplink' => 100,
            'max_downlink' => 100,
            'max_jitter' => 30,
            'max_rtt' => 50,
        ]);
        profiles::create([
            'name' => 'streaming',
            'count' => 1,
            'n_packets' => 100,
            'p_interval' => 40,
            'p_size' => 50,
            'w_time' => 2000,
            'dscp' => 26,
            'max_loss' => 1,
            'max_uplink' => 100,
            'max_downlink' => 100,
            'max_jitter' => 30,
            'max_rtt' => 250,
        ]);
        profiles::create([
            'name' => 'PcoIP',
            'count' => 1,
            'n_packets' => 100,
            'p_interval' => 40,
            'p_size' => 50,
            'w_time' => 2000,
            'dscp' => 46,
            'max_loss' => 1,
            'max_uplink' => 100,
            'max_downlink' => 100,
            'max_jitter' => 30,
            'max_rtt' => 150,
        ]);


        $role = Role::find(1);


        $user->roles()->attach($role);

    }
}