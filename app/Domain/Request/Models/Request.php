<?php

namespace Domain\Request\Models;

use Domain\Attestation\Models\AttestationType;
use Domain\Authorization\Models\Role;
use Domain\Broker\Models\Broker;
use Domain\Delivery\Models\Delivery;
use Domain\Request\States\Approved;
use Domain\Request\States\Cancelled;
use Domain\Request\States\Delivered;
use Domain\Request\States\RequestState;
use Domain\Request\States\Pending;
use Domain\Request\States\PendingToCancelled;
use Domain\Request\States\PendingToRejected;
use Domain\Request\States\Rejected;
use Domain\Request\States\Validated;
use Domain\User\Models\User;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\ModelStates\HasStates;
use Support\HasUuid;

class Request extends Model
{
    use HasFactory;
    use HasStates;
    use HasUuid;
    use SoftDeletes;
    use Filterable;

    protected $guarded = ['id'];

    protected $casts = [
        'approved_at' => 'date:d/m/Y',
        'validated_at' => 'date:d/m/Y',
        'delivered_at' => 'datetime:d/m/Y H:i:s',
        'aborted_at' => 'date:d/m/Y',
        'expected_at' => 'date:d/m/Y',
        'created_at' => 'date:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
        'state' => RequestState::class
    ];

    protected static function booted()
    {
        static::updating(function ($request) {
            if ($request->isDirty('quantity_approved')) {
                $request->approved_at = now();
            }

            if ($request->isDirty('quantity_delivered')) {
                $request->delivered_at = now();
            }

            if ($request->isDirty('quantity_validated')) {
                $request->validated_at = now();
            }
        });

        static::addGlobalScope('activeRequest', function ($query) {
            $query->whereHas('broker', function ($builder) {
                $builder->whereNull('deleted_at');
            });
        });
    }

    public function scopeAllowedForUser($query, User $user)
    {
        if ($user->isBroker()) {
            return $query
                ->onlyParent()
                ->where('broker_id', $user->current_broker_id)
                ->whereState('state', [Pending::$name, Cancelled::$name, Validated::$name, Approved::$name, Rejected::$name, Delivered::$name]);
        }

        if ($user->hasRole(Role::VALIDATOR)) {
            return $query
                ->onlyParent()
                ->whereHas('broker', function ($builder) use ($user) {
                    return $builder->where('department_id', $user->departments()->first()->id);
                })
                ->whereState('state', [Pending::$name, Approved::$name, Validated::$name, Cancelled::$name, Rejected::$name, Delivered::$name]);
        }

        if ($user->hasRole(Role::SUPERVISOR)) {
            return $query
                ->onlyParent()
                ->whereState('state', [Validated::$name, Delivered::$name, Approved::$name]);
        }

        if ($user->hasRole(Role::MANAGER)) {
            return $query->whereState('state', [Validated::$name, Delivered::$name])->onlyParent();
        }

        return $query;
    }

    public function scopeOnlyParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function isPending(): bool
    {
        return $this->state::$name === Pending::$name;
    }

    public function isValidated(): bool
    {
        return $this->state::$name === Validated::$name;
    }

    public function isDelivered(): bool
    {
        return $this->state::$name === Delivered::$name;
    }

    public function isCancelled(): bool
    {
        return $this->state::$name === Cancelled::$name;
    }

    public function isApproved(): bool
    {
        return $this->state::$name === Approved::$name;
    }

    public function isRejected(): bool
    {
        return $this->state::$name === Rejected::$name;
    }

    public function isOfType(int $type): bool
    {
        return $this->attestation_type_id === $type;
    }

    public function generateRelatedInquiry(): void
    {
        if ($this->isOfType(AttestationType::YELLOW)) {
            $request = $this->replicate(['uuid', 'attestation_type_id']);
            $request->parent_id = $this->id;
            $request->uuid = Str::uuid();
            $request->attestation_type_id = AttestationType::BROWN;
            $request->save();
        }
    }

    public function attestationType()
    {
        return $this->belongsTo(AttestationType::class);
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function discussions()
    {
        return $this->hasMany(RequestDiscussion::class);
    }

    public function isRelated(): bool
    {
        return (null !== $this->parent) || (null !== $this->child);
    }

    public function related()
    {
        return $this->child ?: $this->parent;
    }

    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function child()
    {
        return $this->hasOne(__CLASS__, 'parent_id');
    }
}
