<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
class EmailController extends Controller
{
    public function index()
    {
        $data = array('nama'=>"Wawan Hartawan");
        Mail::send('emailregister', $data, function($message) {
           $message->to('kabiro.ti.ksb@gmail.com', 'Wawan Hartawan')->subject
              ('Pendaftaran AHMaD Project');
           $message->from('ahmad@gmail.com','AHMaD Project');
        });
        echo "HTML Email Sent. Check your inbox.";

    }}
