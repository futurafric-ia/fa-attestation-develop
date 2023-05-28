<?php

namespace Domain\Supply\Jobs;

use DB;
use Domain\Attestation\Models\Attestation;
use Domain\Attestation\States\Available;
use Domain\Supply\Events\SupplyDone;
use Domain\Supply\Events\SupplyFailed;
use Domain\Supply\Events\SupplyStarted;
use Domain\Supply\Models\Supply;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\LazyCollection;

class GenerateAttestations implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    protected $supply;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Supply $supply)
    {
        $this->supply = $supply;
    }

    public function handle()
    {
        event(new SupplyStarted($this->supply));

        DB::transaction(function () {
            LazyCollection::make(function () {
                for ($number = $this->supply['range_start']; $number <= $this->supply['range_end']; $number++) {
                    yield $number;
                }
            })
            ->chunk(1000)
            ->each(function ($numbers) {
                $payload = [];

                foreach ($numbers as $number) {
                    $payload[] = [
                        'attestation_number' => $number,
                        'attestation_type_id' => $this->supply->attestation_type_id,
                        'supply_id' => $this->supply->id,
                        'state' => Available::$name,
                        'source' => 'system',
                        'anterior' => $this->supply->isAnterior(),
                        'created_at' => now()->format('Y-m-d'),
                        'updated_at' => now()->format('Y-m-d'),
                    ];
                }

                Attestation::insert($payload);
            });
        });

        event(new SupplyDone($this->supply));
    }

    public function failed(\Throwable $throwable)
    {
        event(new SupplyFailed($this->supply, $throwable->getMessage()));
    }
}
