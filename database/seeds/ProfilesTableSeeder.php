<?php

use Illuminate\Database\Seeder;
use App\profiles;
use App\User;
use App\Role;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
 
     * @return void
     */
    public function run()
    {

        profiles::create(['name' => 'VoIP', 'count' => 1, 'n_packets' => 50, 'p_size' => 200, 'w_time' => 200, 'dscp' => 46, 'p_interval' => 20, 'rtt_g' => 150, 'rtt_r' => 300, 'uplink_g' => 30, 'uplink_r' => 60, 'downlink_g' => 30, 'downlink_r' => 60, 'delay_g' => 150, 'delay_r' => 300, 'downlink_bw_g' => 0.1, 'downlink_bw_r' => 0.8, 'uplink_bw_g' => 0.1, 'uplink_bw_r' => 0.8, 'jitter_g' => 15, 'jitter_r' => 30, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'Gaming', 'count' => 1, 'n_packets' => 20, 'p_size' => 100, 'w_time' => 200, 'dscp' => 34, 'p_interval' => 50, 'rtt_g' => 20, 'rtt_r' => 50, 'uplink_g' => 10, 'uplink_r' => 50, 'downlink_g' => 10, 'downlink_r' => 50, 'delay_g' => 20, 'delay_r' => 100, 'downlink_bw_g' => 10, 'downlink_bw_r' => 1.0, 'uplink_bw_g' => 3.0, 'uplink_bw_r' => 1.0, 'jitter_g' => 10, 'jitter_r' => 30, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'Streaming', 'count' => 1, 'n_packets' => 30, 'p_size' => 1200, 'w_time' => 200, 'dscp' => 36, 'p_interval' => 50, 'rtt_g' => 100, 'rtt_r' => 200, 'uplink_g' => 50, 'uplink_r' => 100, 'downlink_g' => 50, 'downlink_r' => 50, 'delay_g' => 50, 'delay_r' => 100, 'downlink_bw_g' => 5, 'downlink_bw_r' => 3.0, 'uplink_bw_g' => 1.0, 'uplink_bw_r' => 3.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'PCoIP', 'count' => 1, 'n_packets' => 33, 'p_size' => 500, 'w_time' => 200, 'dscp' => 40, 'p_interval' => 30, 'rtt_g' => 100, 'rtt_r' => 150, 'uplink_g' => 50, 'uplink_r' => 100, 'downlink_g' => 50, 'downlink_r' => 100, 'delay_g' => 100, 'delay_r' => 150, 'downlink_bw_g' => 5, 'downlink_bw_r' => 2.0, 'uplink_bw_g' => 2.0, 'uplink_bw_r' => 1.0, 'jitter_g' => 15, 'jitter_r' => 30, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'Internet Browsing', 'count' => 1, 'n_packets' => 10, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 8, 'p_interval' => 50, 'rtt_g' => 150, 'rtt_r' => 300, 'uplink_g' => 100, 'uplink_r' => 200, 'downlink_g' => 100, 'downlink_r' => 200, 'delay_g' => 150, 'delay_r' => 300, 'downlink_bw_g' => 10, 'downlink_bw_r' => 5.0, 'uplink_bw_g' => 3.0, 'uplink_bw_r' => 1.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 1.0, 'organization_id' =>1, 'loss_r' => 5.0]);
        profiles::create(['name' => 'Videoconferencing', 'count' => 1, 'n_packets' => 50, 'p_size' => 300, 'w_time' => 200, 'dscp' => 34, 'p_interval' => 20, 'rtt_g' => 150, 'rtt_r' => 300, 'uplink_g' => 50, 'uplink_r' => 100, 'downlink_g' => 50, 'downlink_r' => 50, 'delay_g' => 150, 'delay_r' => 300, 'downlink_bw_g' => 2, 'downlink_bw_r' => 1.0, 'uplink_bw_g' => 2.0, 'uplink_bw_r' => 1.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'FTP Download', 'count' => 1, 'n_packets' => 10, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 10, 'p_interval' => 50, 'rtt_g' => 100, 'rtt_r' => 200, 'uplink_g' => 100, 'uplink_r' => 200, 'downlink_g' => 100, 'downlink_r' => 200, 'delay_g' => 100, 'delay_r' => 200, 'downlink_bw_g' => 20, 'downlink_bw_r' => 10.0, 'uplink_bw_g' => 1.0, 'uplink_bw_r' => 0.5, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'FTP Upload', 'count' => 1, 'n_packets' => 10, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 12, 'p_interval' => 50, 'rtt_g' => 100, 'rtt_r' => 200, 'uplink_g' => 100, 'uplink_r' => 200, 'downlink_g' => 100, 'downlink_r' => 200, 'delay_g' => 100, 'delay_r' => 200, 'downlink_bw_g' => 5, 'downlink_bw_r' => 3.0, 'uplink_bw_g' => 10.0, 'uplink_bw_r' => 5.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'General Video Streaming', 'count' => 1, 'n_packets' => 20, 'p_size' => 1200, 'w_time' => 200, 'dscp' => 36, 'p_interval' => 50, 'rtt_g' => 100, 'rtt_r' => 200, 'uplink_g' => 30, 'uplink_r' => 60, 'downlink_g' => 30, 'downlink_r' => 60, 'delay_g' => 50, 'delay_r' => 100, 'downlink_bw_g' => 25, 'downlink_bw_r' => 5.0, 'uplink_bw_g' => 1.0, 'uplink_bw_r' => 0.5, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'Cloud Services (SaaS)', 'count' => 1, 'n_packets' => 10, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 32, 'p_interval' => 50, 'rtt_g' => 100, 'rtt_r' => 300, 'uplink_g' => 100, 'uplink_r' => 300, 'downlink_g' => 100, 'downlink_r' => 300, 'delay_g' => 50, 'delay_r' => 100, 'downlink_bw_g' => 20, 'downlink_bw_r' => 10.0, 'uplink_bw_g' => 20.0, 'uplink_bw_r' => 10.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'E_commerce Transactions', 'count' => 1, 'n_packets' => 20, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 26, 'p_interval' => 50, 'rtt_g' => 100, 'rtt_r' => 300, 'uplink_g' => 100, 'uplink_r' => 300, 'downlink_g' => 100, 'downlink_r' => 300, 'delay_g' => 50, 'delay_r' => 100, 'downlink_bw_g' => 20, 'downlink_bw_r' => 10.0, 'uplink_bw_g' => 20.0, 'uplink_bw_r' => 10.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'Social Media Platforms', 'count' => 1, 'n_packets' => 20, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 26, 'p_interval' => 50, 'rtt_g' => 150, 'rtt_r' => 500, 'uplink_g' => 100, 'uplink_r' => 300, 'downlink_g' => 100, 'downlink_r' => 300, 'delay_g' => 50, 'delay_r' => 100, 'downlink_bw_g' => 10, 'downlink_bw_r' => 5.0, 'uplink_bw_g' => 10.0, 'uplink_bw_r' => 5.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'Email Services', 'count' => 1, 'n_packets' => 20, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 10, 'p_interval' => 50, 'rtt_g' => 100, 'rtt_r' => 300, 'uplink_g' => 100, 'uplink_r' => 300, 'downlink_g' => 100, 'downlink_r' => 300, 'delay_g' => 50, 'delay_r' => 100, 'downlink_bw_g' => 20, 'downlink_bw_r' => 10.0, 'uplink_bw_g' => 20.0, 'uplink_bw_r' => 10.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'Remote Desktop Services', 'count' => 1, 'n_packets' => 33, 'p_size' => 500, 'w_time' => 200, 'dscp' => 26, 'p_interval' => 30, 'rtt_g' => 100, 'rtt_r' => 150, 'uplink_g' => 50, 'uplink_r' => 100, 'downlink_g' => 50, 'downlink_r' => 100, 'delay_g' => 100, 'delay_r' => 150, 'downlink_bw_g' => 5, 'downlink_bw_r' => 2.0, 'uplink_bw_g' => 2.0, 'uplink_bw_r' => 1.0, 'jitter_g' => 15, 'jitter_r' => 30, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'Online Education Platforms', 'count' => 1, 'n_packets' => 10, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 26, 'p_interval' => 100, 'rtt_g' => 150, 'rtt_r' => 350, 'uplink_g' => 100, 'uplink_r' => 300, 'downlink_g' => 100, 'downlink_r' => 300, 'delay_g' => 50, 'delay_r' => 100, 'downlink_bw_g' => 10, 'downlink_bw_r' => 5.0, 'uplink_bw_g' => 10.0, 'uplink_bw_r' => 5.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'Large File Transfers', 'count' => 1, 'n_packets' => 20, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 12, 'p_interval' => 50, 'rtt_g' => 150, 'rtt_r' => 500, 'uplink_g' => 100, 'uplink_r' => 300, 'downlink_g' => 100, 'downlink_r' => 300, 'delay_g' => 50, 'delay_r' => 100, 'downlink_bw_g' => 50, 'downlink_bw_r' => 20.0, 'uplink_bw_g' => 50.0, 'uplink_bw_r' => 20.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'IoT Devices', 'count' => 1, 'n_packets' => 30, 'p_size' => 100, 'w_time' => 200, 'dscp' => 0, 'p_interval' => 50, 'rtt_g' => 200, 'rtt_r' => 300, 'uplink_g' => 100, 'uplink_r' => 300, 'downlink_g' => 100, 'downlink_r' => 100, 'delay_g' => 100, 'delay_r' => 100, 'downlink_bw_g' => 5, 'downlink_bw_r' => 3.0, 'uplink_bw_g' => 300.0, 'uplink_bw_r' => 3.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'Online Gaming (Multiplayer)', 'count' => 1, 'n_packets' => 33, 'p_size' => 120, 'w_time' => 200, 'dscp' => 34, 'p_interval' => 30, 'rtt_g' => 30, 'rtt_r' => 60, 'uplink_g' => 20, 'uplink_r' => 40, 'downlink_g' => 20, 'downlink_r' => 40, 'delay_g' => 30, 'delay_r' => 60, 'downlink_bw_g' => 3, 'downlink_bw_r' => 1.0, 'uplink_bw_g' => 1.0, 'uplink_bw_r' => 0.5, 'jitter_g' => 10, 'jitter_r' => 20, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'Live Video Broadcasting', 'count' => 1, 'n_packets' => 50, 'p_size' => 800, 'w_time' => 200, 'dscp' => 36, 'p_interval' => 20, 'rtt_g' => 150, 'rtt_r' => 300, 'uplink_g' => 50, 'uplink_r' => 100, 'downlink_g' => 100, 'downlink_r' => 200, 'delay_g' => 150, 'delay_r' => 300, 'downlink_bw_g' => 5, 'downlink_bw_r' => 3.0, 'uplink_bw_g' => 10.0, 'uplink_bw_r' => 5.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'Cloud Gaming', 'count' => 1, 'n_packets' => 50, 'p_size' => 800, 'w_time' => 200, 'dscp' => 40, 'p_interval' => 20, 'rtt_g' => 30, 'rtt_r' => 70, 'uplink_g' => 20, 'uplink_r' => 50, 'downlink_g' => 20, 'downlink_r' => 50, 'delay_g' => 30, 'delay_r' => 70, 'downlink_bw_g' => 35, 'downlink_bw_r' => 20.0, 'uplink_bw_g' => 10.0, 'uplink_bw_r' => 5.0, 'jitter_g' => 10, 'jitter_r' => 20, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'VPN', 'count' => 1, 'n_packets' => 20, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 24, 'p_interval' => 50, 'rtt_g' => 100, 'rtt_r' => 200, 'uplink_g' => 50, 'uplink_r' => 100, 'downlink_g' => 50, 'downlink_r' => 100, 'delay_g' => 100, 'delay_r' => 200, 'downlink_bw_g' => 10, 'downlink_bw_r' => 5.0, 'uplink_bw_g' => 5.0, 'uplink_bw_r' => 2.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'Web Conferencing', 'count' => 1, 'n_packets' => 20, 'p_size' => 800, 'w_time' => 200, 'dscp' => 34, 'p_interval' => 50, 'rtt_g' => 150, 'rtt_r' => 300, 'uplink_g' => 50, 'uplink_r' => 100, 'downlink_g' => 50, 'downlink_r' => 100, 'delay_g' => 100, 'delay_r' => 300, 'downlink_bw_g' => 2, 'downlink_bw_r' => 1.0, 'uplink_bw_g' => 2.0, 'uplink_bw_r' => 1.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.5, 'organization_id' =>1, 'loss_r' => 1.0]);
        profiles::create(['name' => 'Data Backup and Sync', 'count' => 1, 'n_packets' => 20, 'p_size' => 1500, 'w_time' => 200, 'dscp' => 12, 'p_interval' => 50, 'rtt_g' => 150, 'rtt_r' => 300, 'uplink_g' => 100, 'uplink_r' => 200, 'downlink_g' => 100, 'downlink_r' => 200, 'delay_g' => 150, 'delay_r' => 300, 'downlink_bw_g' => 50, 'downlink_bw_r' => 20.0, 'uplink_bw_g' => 20.0, 'uplink_bw_r' => 10.0, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1, 'organization_id' =>1, 'loss_r' => 0.5]);
        profiles::create(['name' => 'Music Streaming', 'count' => 1, 'n_packets' => 20, 'p_size' => 600, 'w_time' => 200, 'dscp' => 36, 'p_interval' => 50, 'rtt_g' => 50, 'rtt_r' => 300, 'uplink_g' => 50, 'uplink_r' => 100, 'downlink_g' => 50, 'downlink_r' => 100, 'delay_g' => 50, 'delay_r' => 100, 'downlink_bw_g' => 0.3, 'downlink_bw_r' => 0.1, 'uplink_bw_g' => 1.0, 'uplink_bw_r' => 0.5, 'jitter_g' => 30, 'jitter_r' => 50, 'loss_g' => 0.1,  'organization_id' =>1, 'loss_r' => 1.0 ]);
        
    }
}