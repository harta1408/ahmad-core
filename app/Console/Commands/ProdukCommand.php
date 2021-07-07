<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\ProdukLacak;
use App\Models\KirimProduk;
use App\Http\Controllers\BimbinganController;

class ProdukCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periksa:kirimproduk {noresi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pemeriksaan Pengiriman Produk';

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
        $noresi = $this->argument('noresi');

        ProdukLacak::create(['no_resi' => $noresi,
            'kurir' => 'TEKE',
            'tanggal' => date("Y-m-d H:i:s"),
            'deskripsi' => 'Terima permintaan pick up dari [AHMaD]',
        ]);
        ProdukLacak::create(['no_resi' => $noresi,
            'kurir' => 'TEKE',
            'tanggal' => date("Y-m-d H:i:s"),
            'deskripsi' => 'Paket di Input Kurir',
        ]);
        ProdukLacak::create(['no_resi' => $noresi,
            'kurir' => 'TEKE',
            'tanggal' => date("Y-m-d H:i:s"),
            'deskripsi' => 'Paket sampe Gudang Pengirim',
        ]);
        ProdukLacak::create(['no_resi' => $noresi,
            'kurir' => 'TEKE',
            'tanggal' => date("Y-m-d H:i:s"),
            'deskripsi' => 'Paket dalam perjalan ke Gudang Kota Penerima',
        ]);
        ProdukLacak::create(['no_resi' => $noresi,
            'kurir' => 'TEKE',
            'tanggal' => date("Y-m-d H:i:s"),
            'deskripsi' => 'Paket sampai gudang kota penerima',
        ]);
        ProdukLacak::create(['no_resi' => $noresi,
            'kurir' => 'TEKE',
            'tanggal' => date("Y-m-d H:i:s"),
            'deskripsi' => 'Paket dikirimkan ke alamat tujuan',
        ]);
        ProdukLacak::create(['no_resi' => $noresi,
            'kurir' => 'TEKE',
            'tanggal' => date("Y-m-d H:i:s"),
            'deskripsi' => 'Paket diterima oleh Keluarga',
        ]);


        #update bimbingan produk telah diterima santri
        $mulai=date("Y-m-d H:i:s");
        $santriid=KirimProduk::where('kirim_no_resi',$noresi)->first()->santri_id;
        $bimbingancontrol=new BimbinganController;
        $bimbingancontrol->bimbinganSantriMulai($santriid,$mulai);
    }
}
