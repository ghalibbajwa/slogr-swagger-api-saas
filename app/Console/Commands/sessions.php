<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\schedular\schedularController;
class sessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        \Log::info("Session Cron is working fine!");
        $sch=new schedularController();
        $sch->schedule();
    }
}
