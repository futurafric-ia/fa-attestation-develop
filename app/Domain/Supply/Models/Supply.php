<?php

namespace Domain\Supply\Models;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\Models\AttestationType;
use Domain\Logger\Models\JobBatch;
use Domain\Supply\States\Done;
use Domain\Supply\States\Failed;
use Domain\Supply\States\Pending;
use Domain\Supply\States\Running;
use Domain\Supply\States\SupplyState;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;

class Supply extends Model
{
    use HasStates;
    use HasFactory;
    use Filterable;

    protected $guarded = ['id'];

    protected $casts = [
        'received_at' => 'datetime:d/m/Y H:i',
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
        'state' => SupplyState::class,
    ];

    protected static function booted()
    {
        self::creating(function (Model $model) {
            if ($model->received_at->lessThan(config('saham.start_date'))) {
                $model->code = generateDateId('supplies', 'code', 'APPR', "dmY", $model->received_at->format("dmY"));
            } else {
                $model->code = generateDateId('supplies', 'code', 'APPR');
            }
        });

        static::addGlobalScope('currentSupply', function (Builder $builder) {
            $builder->where('received_at', '>=', config('saham.start_date'));
        });
    }

    protected function registerStates(): void
    {
        $this->addState('state', SupplyState::class)
            ->default(Pending::class)
            ->allowTransition(Pending::class, Running::class)
            ->allowTransition(Running::class, Failed::class)
            ->allowTransition(Failed::class, Running::class)
            ->allowTransition(Running::class, Done::class);
    }

    public function attestationType()
    {
        return $this->belongsTo(AttestationType::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function attestations()
    {
        return $this->hasMany(Attestation::class);
    }

    public function jobBatch()
    {
        return $this->belongsTo(JobBatch::class, 'batch_id');
    }

    public function getTypeNameAttribute()
    {
        return $this->attestationType->name;
    }

    public function isCurrent()
    {
        return $this->received_at->greaterThanOrEqualTo(config('saham.start_date'));
    }

    public function isAnterior()
    {
        return ! $this->isCurrent();
    }
}
