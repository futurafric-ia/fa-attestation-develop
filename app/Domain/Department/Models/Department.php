<?php

namespace Domain\Department\Models;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Support\HasSlug;

class Department extends Model
{
    use HasSlug;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function hasUsers()
    {
        return $this->users()->count() !== 0;
    }
}
