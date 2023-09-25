<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    public function publish($message,$queue)
    {
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel = $connection->channel();
      //  $channel->exchange_declare('test_exchange', 'direct', false, false, false);
        //$channel->queue_declare('agent', false, false, false, false);
        //$channel->queue_bind('agent', 'test_exchange', 'test_key');
        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg,'',$queue);
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
