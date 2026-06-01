<?php

namespace App\Registration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Competition;

class CompetitionRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'registration_code',
        'team_name',
        'contact_name',
        'contact_email',
        'contact_phone',
        'status',
        'phase1_amount',
        'phase1_payment_status',
        'phase1_payment_ref',
        'phase1_paid_at',
        'phase1_data',
        'phase2_amount',
        'phase2_payment_status',
        'phase2_payment_ref',
        'phase2_paid_at',
        'phase2_data'
    ];

    protected $casts = [
        'phase1_data' => 'array',
        'phase2_data' => 'array',
        'phase1_paid_at' => 'datetime',
        'phase2_paid_at' => 'datetime',
        'phase1_amount' => 'decimal:2',
        'phase2_amount' => 'decimal:2',
    ];

    /**
     * Get the competition this registration belongs to.
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
