<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;

class ProjectProtocol extends Model
{
    use HasFactory, Metable;


    protected $table = 'project_protocol';
    protected $metaTable = 'project_protocol_meta';


    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function entity() {
        return $this->belongsTo(Entity::class);
    }


    public function source() {
        return $this->belongsTo(ProjectMap::class,'source_id');
    }

    public function target() {
        return $this->belongsTo(ProjectMap::class,'target_id');
    }

}
