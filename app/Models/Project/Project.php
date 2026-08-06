<?php

namespace App\Models\Project;

use App\Models\User;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withPivot('role_id')->withTimestamps();
    }

    public static function newFactory()
    {
        return ProjectFactory::new();
    }
}
