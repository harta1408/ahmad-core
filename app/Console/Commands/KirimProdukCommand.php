<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Http\Controllers\Service\KirimProdukService;


class KirimProdukCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kirimproduk:lacak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Melacak Pengiriman Produk';

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
        $kirim=new KirimProdukService;
        $kirim->lacakPengirimanHarian();
    }
}
