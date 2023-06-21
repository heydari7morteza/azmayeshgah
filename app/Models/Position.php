<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    public function devicePositions() {
        return $this->hasMany(DevicePosition::class);
    }

    protected $guarded  = [
        'device_type',
        'name',
        'type',
    ];

}
