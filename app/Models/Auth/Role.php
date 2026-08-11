<?php

namespace App\Models\Auth;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public static function newFactory()
    {
        return RoleFactory::new();
    }
}
