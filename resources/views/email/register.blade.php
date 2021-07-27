<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <style>
    .teks {
    width: 343px;
    height: 60px;
    margin: 24px 16px 48px;
    font-family: Poppins;
    font-size: 12px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.67;
    letter-spacing: normal;
    text-align: left;
    color: var(--black);
    }
    button {
        width: 311px;
        height: 52px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin: 48px 3.3px 0 32px;
        padding: 16px 20px;
        object-fit: contain;
        border-radius: 14px;
        box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.17), 0 0 3px 0 rgba(0, 0, 0, 0.08);
        background-color: var(--goldd);
    }
    </style>
</head>
<body>
 <h3>Assalamualaikum warahmatullahi wabarakatuh</h3>
 <h1>{!!$name!!}</h1>
 {{-- <h1>Wawan Hartawan</h1> --}}
<div class="teks">
    Untuk menindaklanjuti pendaftaran, Silakan lakukan aktivasi account untuk melanjutkan proses registrasi 
    dengan membuat password melalui klik tombol di bawah ini :
</div>
<a href="{{ $url }}" class="button button-primary" target="_blank">Verfikasi Email</a>

<img src="images/button.png"
     srcset="images/button@2x.png 2x,
             images/button@3x.png 3x"
     class="button">

<div class="teks">
    apabila tombol diatas tidak muncul, bisa klik alamat sebagai berikut
    <a href="{!!$url!!}">{!!$url!!}</a>
</div>
<p>
Power By <a href="http://ahmadproject.org">AHMaD Project</a>
</body>
</html>


