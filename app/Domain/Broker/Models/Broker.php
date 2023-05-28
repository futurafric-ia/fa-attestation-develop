<?php

namespace Domain\Broker\Models;

use App\Support\HasProfilePhoto;
use Domain\Attestation\Models\Attestation;
use Domain\Attestation\States\Attributed;
use Domain\Attestation\States\Used;
use Domain\Broker\Models\Traits\HasOwner;
use Domain\Broker\Models\Traits\HasUsers;
use Domain\Delivery\Models\Delivery;
use Domain\Department\Models\Department;
use Domain\Request\Models\Request;
use Domain\Request\States\Approved;
use Domain\Request\States\Delivered;
use Domain\Request\States\Pending;
use Domain\Request\States\Validated;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\HasSlug;

class Broker extends Model
{
    use HasFactory;
    use HasSlug;
    use HasOwner;
    use HasUsers;
    use HasProfilePhoto;
    use SoftDeletes;
    use Filterable;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeBrokersFromDepartment($query, $department)
    {
        return $query->whereHas('department', function ($builder) use ($department) {
            return $builder->where('id', $department);
        });
    }

    public function scopePendingInqueries($query, $type = null)
    {
        return $query->whereHas('requests', function ($builder) use ($type) {
            $builder->when($type, function ($q) use ($type) {
                $q->where('attestation_type_id', $type);
            });

            return $builder->whereState('state', Pending::class);
        });
    }

    public function scopeDeliveredInqueries($query, $type = null)
    {
        return $query->whereHas('requests', function ($builder) use ($type) {
            $builder->when($type, function ($q) use ($type) {
                $q->where('attestation_type_id', $type);
            });

            return $builder->whereState('state', Delivered::class);
        });
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function attestations()
    {
        return $this->hasMany(Attestation::class, 'current_broker_id');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function hasPendingRequestOfType(int $attestationTypeId, array $except = []): bool
    {
        return $this
                ->requests()
                ->whereNotIn('id', $except)
                ->whereState('state', [Approved::class, Validated::class])
                ->where('attestation_type_id', $attestationTypeId)
                ->count() !== 0;
    }

    /**
     * Verifie si l'intermediaire a consommé son taux minimal d'attestation.
     *
     * @param int $type
     * @return bool
     */
    public function hasConsumedMinimumQuota(int $type): bool
    {
        $attestations = $this
            ->attestations()
            ->whereState('state', [Attributed::class, Used::class,])
            ->where('attestation_type_id', $type)
            ->get();

        if ($attestations->isEmpty()) {
            return true;
        }

        $notUsed = $attestations->filter(function ($attestation) {
            return Attributed::$name === $attestation->state::$name;
        });

        /*
         * Vérifies si le nombre d'attestations non utilisées est inférieure au nombre
         * minimum d'attestations à consommer
         */
        return $notUsed->count() < round(($attestations->count() * $this->minimum_consumption_percentage) * 0.01);
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        return config('saham.logo.default_logo_url');
    }

    /**
     * Get the column that will hold the profile photo url.
     *
     * @return string
     */
    protected function profilePhotoColumn()
    {
        return 'logo_url';
    }

    /**
     * Get the folder that profile photos should be stored in.
     *
     * @return string
     */
    protected function profilePhotoFolder()
    {
        return 'logos';
    }
}
