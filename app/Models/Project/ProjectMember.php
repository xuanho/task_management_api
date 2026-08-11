<?php

namespace App\Models\Project;

use App\Models\Auth\Role;
use App\Models\User;
use Database\Factories\ProjectMemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    protected static function newFactory()
    {
        return ProjectMemberFactory::new();
    }
}
