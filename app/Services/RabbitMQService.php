<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Crypt;
use App\agents;
use App\analytics;
use Stevebauman\Location\Facades\Location;

class RabbitMQService
{
    public function publish($message, $queue)
    {
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel = $connection->channel();
        //  $channel->exchange_declare('test_exchange', 'direct', false, false, false);
        //$channel->queue_declare('agent', false, false, false, false);
        //$channel->queue_bind('agent', 'test_exchange', 'test_key');
        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, '', $queue);
        echo " [x] Sent $message to test_exchange / test_queue.\n";
        $channel->close();
        $connection->close();
    }
    public function consume()
    {
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel = $connection->channel();
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";


            $jsonString = str_replace("'", '"', $msg->body);


            $data = json_decode($jsonString, true);

            if ($data["type"] == 'register') {

                $agent = agents::where('agent_code','=',$data['aid'])->first(); 
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
                }else{
                    return response()->json(['error' => "agent not found"])->setStatusCode(400);
                }

            }
            elseif($data["type"] == 'report'){

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

            }
        };
        $channel->queue_declare('reporting', false, false, false, false);
        $channel->basic_consume('reporting', '', false, true, false, false, $callback);
        echo 'Waiting for new message on test_queue', " \n";

       
        $isConsuming = true;
        while ($isConsuming) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();

    }

}