<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RabbitMQService;
use Log;

class register extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume the register queue';


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
        Log::info('Executing reguster');
        $mqService = new RabbitMQService();
        $mqService->register();
      
    }
}
