<?php

namespace Domain\Attestation\Models;

use Domain\Delivery\Models\Delivery;
use Domain\Request\Models\Request;
use Domain\Scan\Models\Scan;
use Domain\Supply\Models\Supply;
use Illuminate\Database\Eloquent\Model;
use Support\HasSlug;

class AttestationType extends Model
{
    use HasSlug;

    /**
     * !!! IMPORTANT !!!
     * CES VALEURS NE DOIVENT ETRE PAS CHANGEES.
     */
    public const YELLOW = 1;
    public const GREEN = 2;
    public const BROWN = 3;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    public function scopeRequestable($query)
    {
        return $query->where('is_requestable', true);
    }

    public function attestations()
    {
        return $this->hasMany(Attestation::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function scans()
    {
        return $this->hasMany(Scan::class);
    }
}
