<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 
        'user_email', 
        'user_password',
        'user_hash', //untuk link verifikasi email
        'email_verified_at',
        'user_tipe', // 1=donatur 2=santri, 3=pendamping 4=manager, 5=finance, 6=helpdesk, 7=superadmin
        'referensi_id',
    ];

 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function santri(){
        return $this->hasOne('App\Models\Santri','santri_email','user_email');
    }
    public function donatur(){
        return $this->hasOne('App\Models\Donatur','donatur_email','user_email');
    }
}
