<?php

namespace Domain\Attestation\Models;

use Domain\Attestation\States\AttestationState;
use Domain\Attestation\States\Attributed;
use Domain\Attestation\States\Available;
use Domain\Attestation\States\Cancelled;
use Domain\Attestation\States\Returned;
use Domain\Attestation\States\Used;
use Domain\Broker\Models\Broker;
use Domain\Delivery\Models\Delivery;
use Domain\Scan\Models\Scan;
use Domain\Supply\Models\Supply;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\ModelStates\HasStates;
use Storage;
use Support\DealsWithState;

class Attestation extends Model
{
    use SoftDeletes;
    use HasStates;
    use DealsWithState;
    use Filterable;
    use LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
        'state' => AttestationState::class,
    ];

    protected static $logName = 'attestation';

    protected static $logUnguarded = true;

    protected static $logOnlyDirty = true;

    protected static $ignoreChangedAttributes = ['updated_at'];

    protected static $submitEmptyLogs = false;

    protected static function booted()
    {
        static::addGlobalScope('currentAttestation', function (Builder $builder) {
            $builder->where('anterior', false);
        });
    }

    public function scopeDeliverable($query)
    {
        return $query->whereState('state', [Available::class, Returned::class]);
    }

    public function scopeForDelivery($query, Delivery $delivery)
    {
        return $query->whereHas('deliveries', function ($builder) use ($delivery) {
            return $builder->where('deliveries.' . $delivery->getKeyName(), $delivery->getKey());
        });
    }

    public function scopeWithState($query, $state)
    {
        return $query->whereState('state', (array) $state);
    }

    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class);
    }

    public function scans()
    {
        return $this->belongsToMany(Scan::class);
    }

    public function lastScan()
    {
        return $this->belongsTo(Scan::class, 'last_scan_id');
    }

    public function attestationType()
    {
        return $this->belongsTo(AttestationType::class);
    }

    public function currentBroker()
    {
        return $this->belongsTo(Broker::class, 'current_broker_id');
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path
            ? Storage::disk('public')->url("attestations_images/{$this->attestationType->slug}/{$this->image_path}")
            : null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
