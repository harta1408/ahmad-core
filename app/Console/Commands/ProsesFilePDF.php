<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Http\Controllers\Service\DonasiService;

class ProsesFilePDF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proses:filepdf';

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
        $donservice=new DonasiService;
        // $donservice->donasiCicilanPDF('3');
        $donservice->donasiInvoicePDF('3');
    }
}
