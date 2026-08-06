<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
