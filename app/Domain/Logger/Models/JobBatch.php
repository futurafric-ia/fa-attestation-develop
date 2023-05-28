<?php

namespace Domain\Logger\Models;

use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class JobBatch extends Model
{
    use ReadOnlyTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_batches';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'collection',
        'failed_jobs' => 'integer',
        'created_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Get the total number of jobs that have been processed by the batch thus far.
     *
     * @return int
     */
    public function processedJobs()
    {
        return $this->total_jobs - $this->pending_jobs;
    }

    /**
     * Get the percentage of jobs that have been processed (between 0-100).
     *
     * @return int
     */
    public function progress(): int
    {
        return $this->total_jobs > 0 ? round(($this->processedJobs() / $this->total_jobs) * 100) : 0;
    }

    /**
     * Determine if the batch has pending jobs
     *
     * @return bool
     */
    public function hasPendingJobs(): bool
    {
        return $this->pending_jobs > 0;
    }

    /**
     * Determine if the batch has finished executing.
     *
     * @return bool
     */
    public function finished(): bool
    {
        return ! is_null($this->finished_at);
    }

    /**
     * Determine if the batch has job failures.
     *
     * @return bool
     */
    public function hasFailures(): bool
    {
        return $this->failed_jobs > 0;
    }

    /**
     * Determine if all jobs failed.
     *
     * @return bool
     */
    public function failed(): bool
    {
        return $this->failed_jobs === $this->total_jobs;
    }

    /**
     * Determine if the batch has been canceled.
     *
     * @return bool
     */
    public function cancelled(): bool
    {
        return ! is_null($this->cancelled_at);
    }

    /**
     * Determine if the batch has been not been processed yet.
     *
     * @return bool
     */
    public function pending(): bool
    {
        return $this->pending_jobs === $this->total_jobs;
    }

    /**
     * Determine if the batch has is processing jobs.
     *
     * @return bool
     */
    public function running(): bool
    {
        return $this->hasPendingJobs() && $this->total_jobs > $this->pending_jobs;
    }

    public function label(): string
    {
        if ($this->pending()) {
            return 'En attente';
        }

        if ($this->finished()) {
            return 'Terminé';
        }

        if ($this->running()) {
            return 'En cours';
        }

        if ($this->failed()) {
            return 'Echoué';
        }

        return 'Annulé';
    }

    public function color(): string
    {
        if ($this->pending()) {
            return 'bg-gray-400';
        }

        if ($this->finished()) {
            return 'bg-green-500';
        }

        if ($this->running()) {
            return 'bg-orange-500';
        }

        if ($this->failed()) {
            return 'bg-red-500';
        }

        return 'bg-yellow-400';
    }

    public function textColor(): string
    {
        if ($this->pending()) {
            return 'text-gray-50';
        }

        if ($this->finished()) {
            return 'text-gray-50';
        }

        if ($this->running()) {
            return 'text-gray-50';
        }

        if ($this->failed()) {
            return 'text-gray-50';
        }

        return 'text-gray-50';
    }
}
