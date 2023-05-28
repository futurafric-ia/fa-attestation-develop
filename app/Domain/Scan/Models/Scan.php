<?php

namespace Domain\Scan\Models;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\Models\AttestationType;
use Domain\Attestation\States\AttestationState;
use Domain\Broker\Models\Broker;
use Domain\Logger\Models\JobBatch;
use Domain\Scan\States\Done;
use Domain\Scan\States\Failed;
use Domain\Scan\States\Pending;
use Domain\Scan\States\Running;
use Domain\Scan\States\ScanState;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Support\DealsWithState;
use Support\HasUuid;

class Scan extends Model
{
    use HasStates;
    use DealsWithState;
    use HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
        'files_paths' => 'array',
        'query_results' => 'array',
        'parser_results' => 'array',
        'state' => ScanState::class,
        'attestation_state' => AttestationState::class
    ];

    protected $appends = [
        'success_count',
    ];

    public const TYPE_MANUAL = 'manual';
    public const TYPE_OCR = 'ocr';
    public const SOURCE_INTERNAL = 'internal';
    public const SOURCE_EXTERNAL = 'external';

    public function scopeAllowedForUser($query, User $user)
    {
        return $query->where('initiated_by', $user->getKey());
    }

    public function getSuccessCountAttribute()
    {
        return $this->total_count - $this->mismatches_count;
    }

    public function isManual(): bool
    {
        return self::TYPE_MANUAL === $this->type;
    }

    public function isOcr(): bool
    {
        return self::TYPE_OCR === $this->type;
    }

    public function isInternal(): bool
    {
        return self::SOURCE_INTERNAL === $this->type;
    }

    public function isExternal(): bool
    {
        return self::SOURCE_EXTERNAL === $this->type;
    }

    public function isDone(): bool
    {
        return $this->state::$name === Done::$name;
    }

    public function isRunning(): bool
    {
        return $this->state::$name === Running::$name;
    }

    public function isPending(): bool
    {
        return $this->state::$name === Pending::$name;
    }

    public function hasFailed(): bool
    {
        return $this->state::$name === Failed::$name;
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function attestationType()
    {
        return $this->belongsTo(AttestationType::class);
    }

    public function attestations()
    {
        return $this->belongsToMany(Attestation::class);
    }

    public function mismatches()
    {
        return $this->hasMany(ScanMismatch::class);
    }

    public function jobBatch()
    {
        return $this->belongsTo(JobBatch::class, 'batch_id');
    }
}
