<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded  = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function devices() {
        return $this->hasMany(Device::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class,'user_id');
    }

    public function admin_tickets() {
        return $this->hasMany(Ticket::class,'admin_id');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function options() {
        return $this->hasMany(Option::class);
    }

    public function liquids() {
        return $this->hasMany(Liquid::class);
    }


    public function isAdmin()
    {
        if($this->type === 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
