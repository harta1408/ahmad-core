<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    #pembuat : lembaga
    #pendistribusi : lembaga, donatur, santri dan pendamping
    #tabel berita bisa berisi informasi satu arah, muncul di dashboard memberikan inforamasi
    #berita kampanye ditujukan bagi donatur dan santri , berisi masing masing 7
    #video yang berurutan, muncul pada halaman ketikan donatur atau santri akan melakukan proses
    #registrasi, akan muncul untuk maksimal 7 video
    #berita broadcast, ada 3 jenis yaitu broadcash untuk donatur, santri dan pendamping, dikirimkan 
    #bersama sama link referral 
    #berita jenis ketiga adalah berupa informasi yang dikirimkan oleh lembaga ke semua entitas atau
    #grup entitas 
    protected $table='berita';
    protected $fillable=[
        'berita_judul', //judul berita
        'berita_isi', //isi berita
        'hadist_isi_singkat',
        'berita_jenis', //1=berita 2-kampanye 3=broadcast
        'berita_entitas', //0=all 1=donatur 2=santri 3=pendamping 
        'berita_index', //nomor urut berita, khusus untuk kampanye
        'berita_lokasi_gambar', //lokasi gambar
        'berita_lokasi_video', //lokasi video
        'berita_web_link', //alamat web berita (khusud web)
        'berita_status', //0=not aktif 1=aktif 
    ];
}

