<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = ['name', 'logo_url', 'level', 'order', 'link_url', 'active'];
}
