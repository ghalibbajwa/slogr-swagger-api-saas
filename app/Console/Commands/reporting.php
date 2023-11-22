<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RabbitMQService;
use Log;

class reporting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reporting:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume the reporting queue';


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
        Log::info('Executing repot');
        $mqService = new RabbitMQService();
        $mqService->report();
      
       
    }
}
