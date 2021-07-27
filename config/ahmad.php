<?php

return [
    #url base untuk referral
    'referral' =>[
        'development'=>[
            'donatur' => 'http://dev.ahmadproject.org/gabung/donatur/',
            'santri' => 'http://dev.ahmadproject.org/gabung/santri/',
        ],
        'production'=> [
            'donatur' => 'http://ahmadproject.org/gabung/donatur/',
            'santri' => 'http://ahmadproject.org/gabung/santri/',
        ],
    ],
    #url base untuk registrasi
    'register' =>[
    'development'=>[
        'donatur' => 'http://dev.ahmadproject.org/daftar/donatur/',
        'santri' => 'http://dev.ahmadproject.org/daftar/santri/',
        'pedamping' => 'http://dev.ahmadproject.org/daftar/pedamping/',
    ],
    'production'=>[
        'donatur' => 'http://ahmadproject.org/daftar/donatur/',
        'santri' => 'http://ahmadproject.org/daftar/santri/',
        'pedamping' => 'http://ahmadproject.org/daftar/pedamping/',
    ],
],
    #konfiguasi woola messanger
    'woowa' => [
        'whatsapp' => [
            'url' => 'http://116.203.191.58/api/',
            'key' => '5ba6e6972523b6b3805e163d36d344ed602de281d7aea489',
            'method' => [
                'sync' => 'send_message',
                'async' => 'async_send_message',
                'image_sync' => 'send_image_url',
                'image_async' => 'async_send_image_url',
                'file_sync' => 'send_file_url',
                'file_async' => 'async_send_file_url',
            ]
        ],
        'production' => [
            'url' => 'https://rajabiller.fastpay.co.id/transaksi/json.php',
            'uid' => 'SP244115',
            'pin' => '870939',
            'min_deposit' => 100000,
            'method' => [
                'cek_ip' => 'rajabiller.cekip',
                'cek_saldo' => 'rajabiller.balance',
                'cek_harga' => 'rajabiller.harga',
                'cek_info_produk' => 'rajabiller.info_produk',
                'topup_pulsa' => 'rajabiller.pulsa',
                'topup_game' => 'rajabiller.game',
                'inquiry' => 'rajabiller.inq',
                'payment' => 'rajabiller.pay',
                'inquiry_bpjs' => 'rajabiller.bpjsinq',
                'payment_bpjs' => 'rajabiller.bpjspay',
                'data_transaksi' => 'rajabiller.datatransaksi',
                'cetak_ulang' => 'rajabiller.cu',
                'cetak_ulang_detail' => 'rajabiller.cudetail',
                'inquiry_pln' => 'rajabiller.inqpln',
                'payment_pln' => 'rajabiller.paypln',
                'group_produk' => "rajabiller.group_produk",
            ]
        ],
    ]
];
