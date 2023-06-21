<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function admin() {
        return $this->belongsTo(User::class,'admin_id');
    }

    public function device() {
        return $this->belongsTo(Device::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

}
