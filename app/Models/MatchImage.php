<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchImage extends Model
{
    use HasFactory;

    protected $fillable = ['match_id', 'image_url', 'caption', 'order'];

    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }
}
