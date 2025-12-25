<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'is_active', 'description'];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function matches()
    {
        return $this->hasMany(MatchModel::class);
    }
}
