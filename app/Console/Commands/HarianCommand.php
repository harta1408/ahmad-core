<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Http\Controllers\Service\DonasiService;
use App\Http\Controllers\Service\MessageService;
use App\Http\Controllers\Service\BimbinganService;

class HarianCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengingat:harian';

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
        #donasi
        $donasi=new DonasiService;
        $donasi->pengingatDonasi();
        $donasi->pesanJatuhTempo();
        $donasi->randomSantri();

        #bimbingan
        $bimservice=new BimbinganService;
        $bimservice->pengingatBuatSantri();
        $bimservice->pengingatBimbingan();
        $bimservice->pengingatSembunyikan();

        #message
        $msg=new MessageService;
        $msg->hapusNotifikasi();
    }
}
