<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchCommentary extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'minute',
        'type',
        'comment',
    ];

    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }
}
