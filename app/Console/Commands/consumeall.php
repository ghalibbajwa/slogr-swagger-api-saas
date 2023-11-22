<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RabbitMQService;
use Akbansa\LaravelParallelProcessor\ParallelProcessor;


class consumeall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'all:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume the all queues';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        

        $tasks = [
            ["id" => 1, "command" => "php artisan reporting:consume"],
            ["id" => 2, "command" => "php artisan register:consume"]
       
        ];

        $processor = new ParallelProcessor($tasks);
        $processor->start();

        // while (!$processor->isFinished()) {
        //     sleep(1);
        // }

    }
}
