<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;

class Project extends Model
{
    use HasFactory , Metable;

    protected $metaTable = 'projects_meta';

    public function device() {
        return $this->belongsTo(Device::class);
    }

    public function liquids() {
        return $this->hasMany(Liquid::class);
    }

    public function projectMaps() {
        return $this->hasMany(ProjectMap::class);
    }

    public function projectProtocols() {
        return $this->hasMany(ProjectProtocol::class);
    }
}
