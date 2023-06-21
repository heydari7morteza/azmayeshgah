<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;

class Entity extends Model
{
    use HasFactory,Metable;

    protected $table = 'entities';
    protected $metaTable = 'entities_meta';

    public function projectMaps() {
        return $this->hasMany(ProjectMap::class);
    }

    public function projectProtocols() {
        return $this->hasMany(ProjectProtocol::class);
    }

}
