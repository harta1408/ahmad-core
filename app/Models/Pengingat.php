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

    #pembuatan pengingat baru di tangani oleh modulpengingat controller
    #pengiriman pesan update ke pengingat donatur dan pengingat santri ditangani oleh modul
    #pengingat service
    protected $table='pengingat';
    protected $fillable=[
        'pengingat_jenis', //1=sedekah subuh, 2=sedekah jumat 3=sedekah yaumul bidh 
                           //4=Senin 5=Kamis 6=Jumat 7=online meeting 8=offline meeting 9=talkin dzikir
        'pengingat_entitas', //1=donatur 2=santri
        'pengingat_index', //nomor urut pengingat  
        'pengingat_judul', //judul pengingat
        'pengingat_isi', //isi pengingat
        'pengingat_isi_singkat', //versi pendek isi pengingat
        'pengingat_lokasi_gambar', //lokasi gambar
        'pengingat_lokasi_video', //lokasi video
        'pengingat_status', //0=non aktif 1=aktif
    ];
    public function donatur(){
        return $this->belongsToMany('App\Models\Donatur','pengingat_donatur','donatur_id','pengingat_id','id','id')
                    ->as('pengingatdonatur')
                    ->withPivot('pengingat_donatur_respon','pengingat_donatur_status')
                    ->withTimestamps();
    }
    public function santri(){
        return $this->belongsToMany('App\Models\Santri','pengingat_santri','pengingat_id','santri_id','id','id')
                    ->as('pengingatsantri')
                    ->withPivot('pengingat_santri_index','pengingat_santri_respon','pengingat_santri_status')
                    ->withTimestamps();
    }
    public function pendamping(){
        return $this->belongsToMany('App\Models\Pendamping','pengingat_pendamping','pengingat_id','pendamping_id','id','id')
                    ->as('pengingatpendamping')
                    ->withPivot('pengingat_pendamping_santri_respon','pengingat_pendamping_status')
                    ->withTimestamps();
    }
}
