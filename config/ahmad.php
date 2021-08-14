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
    ],
    #url base untuk raja ongkir
    'rajaongkir' =>[
        'key'=>'be17fa82d624c2bc97621dc4a20dd67d',
        'url'=>[
            'province' => 'https://pro.rajaongkir.com/api/province',
            'city' => 'https://pro.rajaongkir.com/api/city',
            'subdistrict' => 'https://pro.rajaongkir.com/api/subdistrict',
            'cost'=>'https://pro.rajaongkir.com/api/cost',
            'intorigin' => 'https://pro.rajaongkir.com/api/v2/internationalOrigin',
            'intdest' => 'https://pro.rajaongkir.com/api/v2/internationalDestination',
            'intcost' => 'https://pro.rajaongkir.com/api/v2/internationalCost',
            'currency' => 'https://pro.rajaongkir.com/api/currency',
            'waybill' => 'https://pro.rajaongkir.com/api/waybill',
        ],
    ],
    #url base untuk moota
    'moota' =>[
        'personaltoken'=>'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJucWllNHN3OGxsdyIsImp0aSI6IjFhODcyYjQwZTRjMmZlZWI0ZTA4YTY5NWIwNWYyMjY4YTFhZTgwY2IyZjEzZWU1OTRkMGFiODMzOGM4ODRjODI5YzkyNTkwMzJiMTA1NzgxIiwiaWF0IjoxNjI4MjY4MDI4LjM2MjQzNywibmJmIjoxNjI4MjY4MDI4LjM2MjQ0MSwiZXhwIjoxNjU5ODA0MDI4LjM1MjMzMywic3ViIjoiMTk3NjEiLCJzY29wZXMiOlsiYXBpIiwiYmFua19yZWFkIiwibXV0YXRpb24iXX0.vzaIEpcHsOyah-7CeO8HOKqb9tr5MmWLqRgpU1WpH8GTanNlFlHjt8BbV-jrI1R2VgXKzYNclg7KNVFqckjNuGP4r002LKwbU26lJIm59ejstxd7v0VSrpS9mrK3iss_OrKAoNqp5bkPHaLfzMeJI17bejNPzk0sx2yh6xJW1wwJeWHAY9sx5X2D7vR7fih5YidA8aDkQcDxG2dCDp5BYFRRnadTfToPfpa2wUchPXiRwqEM4LE6CXs8cne7412m5NzThjGWzy2NFRiTCKzODi9ToxdssxIWNqT8ZxjvcI1y3q70v6nKirFB3IYXpZbO_0-JB8INpLEJZldXutFQnefXQ1I0Hw-IRtX35bBfo33_W1-p_viLRLRyPi-IQ9Mhj4Hqw6O4PaWNj5NKWrTQxTOFWfV8fF6J_YEs0Tk75eW6EBmqHizAtcpPL55BtJvmo2-Gh069qeTeWKh101eXaUy89K9AVcuLCYlpufUZUkMjqC9Hve2xAhKfc4RZdgYri2FycXzbCTVvft_fH_KuaQm0Txc__Q5fxm8Z7q7K65aZPNAtoi043FnpmfveOREw7ZJUX6HxfNLrp9ryGdAsn90XQvi4VFhn0xdA1rM3fT9EnlooGopjRsPu0vif2dqY-UcSPPGi-XI_gIpuV104MzU_AtvCWNOYKVZA7GzPBUs',
        'url'=>[
            'bank' => 'https://app.moota.co/api/v2/bank',
            'mutasi' => 'https://app.moota.co/api/v2/mutation', 
        ],
    ],
];
