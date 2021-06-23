<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\VerifyEmail;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'hash_code', //untuk link verifikasi email
        'email_verified_at', //waktu verifikasi email
        'tipe', // 1=donatur 2=santri, 3=pendamping 4=manager, 5=helpdesk, 9=superadmin
        'approve', //0:belum disetujui 1:setujui
        'gmail_state', //status user gmail, 0=not gmail, 1=user gmail
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
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail); // my notification
    }
    public function santri(){
        return $this->hasOne('App\Models\Santri','santri_email','email');
    }
    public function donatur(){
        return $this->hasOne('App\Models\Donatur','donatur_email','email');
    }
}
