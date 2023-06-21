<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;

class Device extends Model
{
    use HasFactory,Metable;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function projects() {
        return $this->hasMany(Project::class);
    }

    public function devicePositions() {
        return $this->hasMany(DevicePosition::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

}
