<?php

namespace Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        self::saving(function (Model $model): void {
            if (! $model->slug) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function scopeWhereSlug(Builder $builder, string $uuid): Builder
    {
        return $builder->where('slug', $uuid);
    }

    public function scopeFindBySlug(Builder $builder, string $slug): self
    {
        return $builder->where('slug', $slug)->first();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getRouteKey(): string
    {
        return $this->slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
