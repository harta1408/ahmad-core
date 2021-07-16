<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengingat extends Model
{
    #tabel berisi pengingat dengan tujuan donatur dan santri
    #untuk donatur, pengingat dikirimkan oleh sistem secara otomatis sesuai dengan jenis
    #donasi yang diberikan yaitu subuh(harian), jumat(pekanan) dan yaumul bidh(bulanan)
    #untuk santri, pengingat dikirimkan oleh sistem secara otomatis untuk pilihan 
    #senin atau kamis berupa konten video dan jumat untuk konten image
    #dikirimkan sebanyak setiap bulan (10 kali) selama masa bimbingan
    #pengingat juga dapat di buat dan dikirimkan oleh pendamping untuk kententuan 
    #undangan online meeting, dikirimkan sebanyak 10 kali selama masa bimbingan
    #undangan offline meeting/pengajian, dikirimkan sebanyak 2 kali selama masa bimbingan
    #undangan talkin dzikir sebanyak 1 atau 2 kali selama bimbingan
    protected $table='pengingat';
    protected $fillable=[
        'pengingat_jenis', //1=sedekah subuh, 2=sedekah jumat 3=sedekah yaumul bidh 4=Senin & Kamis 5=Jumat 6=online meeting 7=offline meeting 8=talkin dzikir
        'pengingat_entitas', //1=donatur 2=santri
        'pengingat_index', //nomor urut pengingat  
        'pengingat_judul', //judul pengingat
        'pengingat_isi', //isi pengingat
        'pengingat_lokasi_gambar', //lokasi gambar
        'pengingat_lokasi_video', //lokasi video
        'pengingat_status', //0=non aktif 1=aktif
    ];
}
