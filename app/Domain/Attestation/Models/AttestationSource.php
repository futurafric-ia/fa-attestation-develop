<?php

namespace Domain\Attestation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttestationSource extends Model
{
    use HasFactory;

    public function attestations()
    {
        return $this->hasMany(Attestation::class, 'source', 'name');
    }
}
