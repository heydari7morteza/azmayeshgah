<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;

class ProjectMap extends Model
{
    use HasFactory, Metable;

    protected $table = 'project_map';
    protected $metaTable = 'project_map_meta';


    public function project() {
        return $this->belongsTo(Project::class);
    }


    public function devicePosition() {
        return $this->belongsTo(DevicePosition::class);
    }


    public function entity() {
        return $this->belongsTo(Entity::class);
    }

    public function sources() {
        return $this->hasMany(ProjectProtocol::class,'source_id');
    }

    public function targets() {
        return $this->hasMany(ProjectProtocol::class,'target_id');
    }

}
