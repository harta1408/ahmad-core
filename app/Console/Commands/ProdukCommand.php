<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\ProdukLacak;
use App\Models\KirimProduk;
use App\Http\Controllers\Service\DonasiService;

class ProdukCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:eksekusi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'debugin purpose only';

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
       $ds=new DonasiService;
       $ds->randomSantri();
    }
}
