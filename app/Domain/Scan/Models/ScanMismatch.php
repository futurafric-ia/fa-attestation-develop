<?php

namespace Domain\Scan\Models;

use Domain\Attestation\Models\AttestationType;
use Illuminate\Database\Eloquent\Model;
use Storage;

class ScanMismatch extends Model
{
    protected $guarded = ['id'];

    public function scan()
    {
        return $this->belongsTo(Scan::class);
    }

    public function attestationType()
    {
        return $this->belongsTo(AttestationType::class);
    }

    public function getImageUrlAttribute()
    {
        return Storage::disk('public')->url("attestations_images/{$this->attestationType->slug}/{$this->image_path}");
    }
}
