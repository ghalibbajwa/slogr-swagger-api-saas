<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Crypt;
use App\agents;
use App\analytics;
use Stevebauman\Location\Facades\Location;
use App\latest_analytics;
class RabbitMQService
{

    function __construct()
    {
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel = $connection->channel();

        $channel->exchange_declare('sessions', 'fanout', false, false, false);

        $channel->close();
        $connection->close();

    }
    public function publish($message, $queue)
    {
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel = $connection->channel();
        //  $channel->exchange_declare('test_exchange', 'direct', false, false, false);
        //$channel->queue_declare('agent', false, false, false, false);
        //$channel->queue_bind('agent', 'test_exchange', 'test_key');
        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg,  $queue);
        echo " [x] Sent $message to test_exchange / test_queue.\n";
        $channel->close();
        $connection->close();
    }
    public function report()
    {
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel = $connection->channel();
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";


            $jsonString = str_replace("'", '"', $msg->body);


            $data = json_decode($jsonString, true);

            if ($data["type"] == 'register') {

                $agent = agents::where('agent_code', '=', $data['aid'])->first();
                if ($agent) {
                    $agent->ipaddress = $data['ipaddress'];
                    $agent->machine_name = $data['machine_name'];
                    $agent->arch = $data['arch'];
                    $agent->processor = $data['processor'];
                    $agent->machine = $data['machine'];
                    $agent->platform = $data['platform'];
                    if ($data['env'] == "public") {
                        $loc = Location::get($data['ipaddress']);
                        $agent->lat = $loc->latitude;
                        $agent->long = $loc->longitude;
                        $agent->location = $loc->cityName;
                        $agent->Country = $loc->countryName;
                    } else {
                        $cords = explode(',', $data['cords']);
                        $agent->lat = $cords[0];
                        $agent->long = $cords[1];
                        $agent->location = $data['country'];
                        $agent->Country = $data['country'];
                    }

                    $agent->status = "active";
                    $agent->save();
                } else {
                    return response()->json(['error' => "agent not found"])->setStatusCode(400);
                }

            } elseif ($data["type"] == 'report') {

                $analytics = new Analytics;

                $analytics->session_id = $data['test-name'];
                $analytics->avg_down = $data['avg-downlink-time(ms)'];
                $analytics->avg_jitter = $data['avg-jitter(ms)'];
                $analytics->avg_rtt = $data['avg-rtt(ms)'];
                $analytics->avg_up = $data['avg-uplink-time(ms)'];
                $analytics->max_down = $data['max-downlink-time(ms)'];
                $analytics->max_jitter = $data['max-jitter(ms)'];
                $analytics->max_rtt = $data['max-rtt(ms)'];
                $analytics->max_up = $data['max-uplink-time(ms)'];
                $analytics->min_down = $data['min-downlink-time(ms)'];
                $analytics->min_jitter = $data['min-jitter(ms)'];
                $analytics->min_rtt = $data['min-rtt(ms)'];
                $analytics->min_up = $data['min-uplink-time(ms)'];
                $analytics->r_packets = $data['received-packets'];
                $analytics->st_down = $data['std-dev-downlink-time(ms)'];
                $analytics->st_up = $data['std-dev-uplink-time(ms)'];
                $analytics->st_rtt = $data['std-dev-rtt(ms)'];
                $analytics->t_packets = $data['total-packets'];
                $analytics->save();


                $latest_analytics = latest_analytics::where('session_id','=',$data['test-name'])->first();
                if($latest_analytics==null){
                    $latest_analytics = new latest_analytics();
                }
                $latest_analytics->session_id = $data['test-name'];
                $latest_analytics->avg_down = $analytics->avg_down;
                $latest_analytics->avg_jitter = $analytics->avg_jitter;
                $latest_analytics->avg_rtt = $analytics->avg_rtt;
                $latest_analytics->avg_up = $analytics->avg_up;
                $latest_analytics->max_down = $analytics->max_down;
                $latest_analytics->max_jitter = $analytics->max_jitter;
                $latest_analytics->max_rtt = $analytics->max_rtt;
                $latest_analytics->max_up = $analytics->max_up;
                $latest_analytics->min_down = $analytics->min_down;
                $latest_analytics->min_jitter = $analytics->min_jitter;
                $latest_analytics->min_rtt = $analytics->min_rtt;
                $latest_analytics->min_up = $analytics->min_up;
                $latest_analytics->r_packets = $analytics->r_packets;
                $latest_analytics->st_down = $analytics->st_down;
                $latest_analytics->st_up = $analytics->st_up;
                $latest_analytics->st_rtt = $analytics->st_rtt;
                $latest_analytics->t_packets = $analytics->t_packets;
                $latest_analytics->save();

            }
        };
        $channel->queue_declare('reporting', false, false, false, false);
        $channel->basic_consume('reporting', '', false, true, false, false, $callback);
        echo 'Waiting for new message on reporting', " \n";


        $isConsuming = true;
        while ($isConsuming) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();

    }




    public function register()
    {
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel = $connection->channel();
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            $jsonString = str_replace("'", '"', $msg->body);
            $data = json_decode($jsonString, true);


            $agent = new agents();
            $agent->agent_code = $data['aid'];
            $agent->ipaddress = $data['public_ipaddress'];
            $agent->private_ip = $data['private_ipaddress'];
            $agent->machine_name = $data['machine_name'];
            $agent->arch = $data['arch'];
            $agent->processor = $data['processor'];
            $agent->machine = $data['machine'];
            $agent->platform = $data['platform'];
            try {
                $loc = Location::get($data['public_ipaddress']);
                $agent->lat = $loc->latitude;
                $agent->long = $loc->longitude;
                $agent->location = $loc->cityName;
                $agent->Country = $loc->countryName;
            } catch (\Exception $e) {

            }
            $agent->bios_serial_numbers = serialize($data['bios_serial_numbers']);
            $agent->disks = serialize($data['disks']);
            $agent->os = $data['os'];
            $agent->status = "pending";
            $agent->type = $data['type'];
            $agent->save();




        };
        $channel->queue_declare('register', false, false, false, false);
        $channel->basic_consume('register', '', false, true, false, false, $callback);
        echo 'Waiting for new message on register', " \n";


        $isConsuming = true;
        while ($isConsuming) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();

    }





}