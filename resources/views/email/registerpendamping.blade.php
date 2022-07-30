<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="https://ahmadproject.org/public/css/bootstrap.min.css" type="text/css">
    
    <style>
        body{
            /* background-color: #fcc604; */
        }
        h1{
            font-weight: bold,
        }
        h1, h3{
            text-align: center;
            font-family: Poppins;
        }
        p {
            font-family: Poppins;
            font-size: 14px;
            font-weight: normal;
        }

    </style>
</head>
<body>
   <div class="container" >
       <p>
       <div class="text-center">         
           <img src="https://ahmadproject.org/public/images/logo.png"
                srcset="https://ahmadproject.org/public/images/logo@2x.png 2x,
                https://ahmadproject.org/public/images/logo@3x.png 3x">
       </p>
    </div>
    <h3>Assalamualaikum warahmatullahi wabarakatuh</h3>
    <h3>AHMaD Support</h3>
    <div >
        <p>
        Terdapat Pengajuan sebagai Penggerak atas nama {!!$nama!!}, 
        Silakan ditindaklanjuti persetujuan/ penolakan melalui menu otorisasi pada Dashboard Gerakan AHSOHA
        </p>
    </div>
    <div class="text-center">        
        <p>
        <a href="{!!$url!!}" style="background-color: #fcc604;;
            border: none;
            color: black;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 16px;" role="button">Login Dashboard</a>
        </p>
    </div>
    <br><br>
    <p>
    <a href="https://ahsoha.id">Gerakan AHSOHA</a>
    </p>
</div>
</body>
</html>


