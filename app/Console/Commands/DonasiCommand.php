<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Donasi;


class DonasiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periksa:rekeningdonasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memeriksa Rekening Donasi';

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
        #di matikan dulu, nanti di ganti dengan proses dari moota
        // Donasi::where('donasi_status','1')->update(['donasi_status'=>'2']);
    }
}
