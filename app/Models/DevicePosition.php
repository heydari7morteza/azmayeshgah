<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;

class DevicePosition extends Model
{
    use HasFactory , Metable;

    protected $table = 'device_positions';
    protected $metaTable = 'device_positions_meta';

    protected $casts = [
        'point_a' => 'array',
        'point_b' => 'array',
        'point_c' => 'array'
    ];

    public function device() {
        return $this->belongsTo(Device::class);
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function projectMaps() {
        return $this->hasMany(ProjectMap::class);
    }
}
