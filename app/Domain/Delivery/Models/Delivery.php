<?php

namespace Domain\Delivery\Models;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\Models\AttestationType;
use Domain\Broker\Models\Broker;
use Domain\Delivery\States\DeliveryState;
use Domain\Delivery\States\Done;
use Domain\Delivery\States\Failed;
use Domain\Delivery\States\Pending;
use Domain\Delivery\States\Running;
use Domain\Request\Models\Request;
use Domain\Logger\Models\JobBatch;
use Domain\User\Models\User;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use Support\AdvancedFilters\Dataviewer;
use Support\DealsWithState;

class Delivery extends Model
{
    use HasStates;
    use Filterable;
    use DealsWithState;
    use Dataviewer;

    protected $allowedFilters = ['quantity', 'broker.code', 'broker.name', 'delivered_at'];

    protected $guarded = ['id'];

    protected $casts = [
        'delivered_at' => 'date:d/m/Y H:i:s',
        'created_at' => 'date:d/m/Y H:i:s',
        'updated_at' => 'date:d/m/Y H:i:s',
        'state' => DeliveryState::class,
    ];

    protected static function booted()
    {
        self::creating(function (Model $model) {
            $model->code = generateDateId('deliveries', 'code', 'LIVR');
            $model->delivered_at = now();
        });

        static::addGlobalScope('activeDelivery', function ($query) {
            $query->whereHas('broker', function ($builder) {
                $builder->whereNull('deleted_at');
            });
        });
    }

    public function scopeAllowedForUser($builder, User $user)
    {
        if ($user->isOfType(User::TYPE_BROKER)) {
            return $builder->where('broker_id', $user->currentBroker->id)->whereState('state', Done::$name);
        }

        if ($user->cannot('delivery.create')) {
            return $builder->whereState('state', Done::$name);
        }

        return $builder;
    }

    public function scopeOfType($builder, $type)
    {
        return $builder->where('attestation_type_id', $type);
    }

    public function isDone()
    {
        return $this->state::$name === Done::$name;
    }

    public function isPending()
    {
        return $this->state::$name === Pending::$name;
    }

    public function hasFailed()
    {
        return $this->state::$name === Failed::$name;
    }

    public function attestations()
    {
        return $this->belongsToMany(Attestation::class);
    }

    public function attestationType()
    {
        return $this->belongsTo(AttestationType::class);
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function jobBatch()
    {
        return $this->belongsTo(JobBatch::class, 'batch_id');
    }

    public function getTypeNameAttribute()
    {
        return $this->attestationType->name;
    }

    /**
     * Retournes le nombre total d'attestation pour un status donné pour cette livraison.
     *
     * @param string $state
     * @param Broker|null $broker
     * @return int
     */
    public function totalAttestationCountForState($state): int
    {
        return Attestation::forDelivery($this)->withState($state)->count();
    }
}
