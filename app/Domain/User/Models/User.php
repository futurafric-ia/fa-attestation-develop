<?php

namespace Domain\User\Models;

use App\Support\HasProfilePhoto;
use Domain\Authorization\Models\Role;
use Domain\Department\Models\Department;
use Domain\User\Models\Traits\HasBrokers;
use Domain\User\Notifications\ResetPasswordNotification;
use Domain\User\Notifications\VerifyEmailNotification;
use Domain\User\Notifications\WelcomeNotification;
use EloquentFilter\Filterable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Spatie\WelcomeNotification\ReceivesWelcomeNotification;
use Support\HasUuid;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasRoles;
    use HasPermissions;
    use MustVerifyEmailTrait;
    use HasUuid;
    use Filterable;
    use SoftDeletes;
    use HasBrokers;
    use HasFactory;
    use CausesActivity;
    use HasProfilePhoto;
    use Impersonate;
    use ReceivesWelcomeNotification;

    /**
     * Pour les utilisateurs qui ont accès à l'administration de la plateforme
     */
    public const TYPE_ADMIN = 'admin';

    /**
     * Pour les utilisateurs intervenants dans le workflow de l'application
     * Ex. Seulement les utilisateurs internes à Saham.
     */
    public const TYPE_USER = 'user';

    /**
     * Pour les intermediaires et toutes personnes pouvant effectués des demandes.
     */
    public const TYPE_BROKER = 'broker';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'to_be_logged_out' => 'boolean',
        'last_login_at' => 'datetime:d/m/Y H:i',
        'email_verified_at' => 'datetime',
        'created_at' => 'date:d/m/Y H:i:s',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_login_at',
        'email_verified_at',
        'password_changed_at',
    ];

    protected $appends = [
        'full_name',
        'main_role_name',
        'main_department_name',
    ];

    public function scopeOnlyDeactivated($query)
    {
        return $query->whereActive(false);
    }

    public function scopeOnlyActive($query)
    {
        return $query->whereActive(true);
    }

    public function scopeOnlySahamUsers($query)
    {
        return $query->whereType(self::TYPE_USER);
    }

    public function scopeOnlyBrokers($query)
    {
        return $query->whereType(self::TYPE_BROKER);
    }

    public function scopeBrokersFromDepartment($query, $departmentId)
    {
        return $query
            ->whereType(self::TYPE_BROKER)
            ->whereHas('departments', function ($builder) use ($departmentId) {
                return $builder->where('departments.id', $departmentId);
            });
    }

    public function scopeValidators($query)
    {
        return $query->role(Role::VALIDATOR);
    }

    public function scopeValidatorsFromDepartment($query, $departmentId)
    {
        return $query
            ->role(Role::VALIDATOR)
            ->whereHas('departments', function ($builder) use ($departmentId) {
                return $builder->where('departments.id', $departmentId);
            });
    }

    public function scopeSupervisors($query)
    {
        return $query->role(Role::SUPERVISOR);
    }

    public function scopeManagers($query)
    {
        return $query->role(Role::MANAGER);
    }

    public function isOfType($type): bool
    {
        return $type === $this->type;
    }

    public function isUser(): bool
    {
        return $this->isOfType(self::TYPE_USER);
    }

    public function isBroker(): bool
    {
        return $this->isOfType(self::TYPE_BROKER);
    }

    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name}";
    }

    public function getMainRoleAttribute()
    {
        return $this->roles()->first();
    }

    public function getMainRoleNameAttribute()
    {
        return optional($this->roles()->first())->name;
    }

    public function getMainDepartmentAttribute()
    {
        return $this->departments()->first();
    }

    public function getMainDepartmentNameAttribute()
    {
        return optional($this->departments()->first())->name;
    }

    public function setPasswordAttribute($password)
    {
        if (Hash::needsRehash($password)) {
            $this->attributes['password'] = Hash::make($password);
        } else {
            $this->attributes['password'] = $password;
        }
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function canImpersonate(): bool
    {
        return $this->can('user.impersonate');
    }

    public function canBeImpersonated(): bool
    {
        return ! $this->isMasterAdmin();
    }

    public function isMasterAdmin(): bool
    {
        return 1 === $this->id;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ADMIN);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }

    public function canChooseAttestationState(): bool
    {
        return $this->can('attestation.scan') && $this->hasRole(Role::MANAGER);
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isVerified(): bool
    {
        return !!$this->email_verified_at;
    }

    public function getPermissionDescriptions(): Collection
    {
        return $this->permissions->pluck('description');
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send the registration verification email.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification());
    }

    /**
     * Send the welcome email after account creation.
     *
     * @param \Carbon\Carbon $validUntil
     */
    public function sendWelcomeNotification(\Carbon\Carbon $validUntil)
    {
        $this->notify(new WelcomeNotification($validUntil));
    }

    protected function getProfilePhotoUrlAttribute()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&color=7F9CF5&background=EBF4FF';
    }
}
