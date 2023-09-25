<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class reportsCollector
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AMQPMessage $message)
    {
        $acknowledgmentMessage = $message->getBody();

        // Your logic to display or process the acknowledgment message
        \Log::info('Acknowledgment Received: ' . $acknowledgmentMessage);


    }
}