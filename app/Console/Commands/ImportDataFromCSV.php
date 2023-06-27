<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\asn_table;

class ImportDataFromCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from CSV file';

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
        
        $file ="assets/asn-laravel.csv";

        $data = array_map('str_getcsv', file($file));

        $headers = array_shift($data);

     

        foreach ($data as $row) {
            $asn=new asn_table;
            $dataRow = array_combine($headers, $row);
            $asn->startip=$dataRow['Start'];
            $asn->endip=$dataRow['End'];
            $asn->asn=$dataRow['ASN'];
            $asn->isp=$dataRow['ISP'];
            $asn->save();
    
        }

    }
}
