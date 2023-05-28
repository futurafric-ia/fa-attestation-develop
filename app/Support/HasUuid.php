<?php

namespace Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        self::creating(function (Model $model) {
            if (null !== $model->uuid) {
                return $model;
            }

            $model->uuid = Str::uuid();
        });
    }

    public function scopeFindByUuid(Builder $builder, string $uuid)
    {
        return $builder->where('uuid', $uuid)->first();
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getRouteKey(): string
    {
        return $this->uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
