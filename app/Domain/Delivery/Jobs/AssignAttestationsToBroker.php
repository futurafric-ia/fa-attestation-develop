<?php

namespace Domain\Delivery\Jobs;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\States\Attributed;
use Domain\Delivery\Events\DeliveryDone;
use Domain\Delivery\Events\DeliveryFailed;
use Domain\Delivery\Events\DeliveryStarted;
use Domain\Delivery\Models\Delivery;
use Domain\Delivery\Models\DeliveryItem;
use Domain\Request\States\Delivered;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AssignAttestationsToBroker implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * @var Delivery
     */
    protected Delivery $delivery;

    protected array $ranges;

    /**
     * Create a new job instance.
     *
     * @param Delivery $delivery
     * @param array $ranges
     */
    public function __construct(Delivery $delivery, array $ranges)
    {
        $this->delivery = $delivery;
        $this->ranges = $ranges;
    }

    public function handle()
    {
        DeliveryStarted::dispatch($this->delivery);

        DB::beginTransaction();

        foreach ($this->ranges as $range) {
            Attestation::whereBetween('attestation_number', [$range['range_start'], $range['range_end']])
                ->get()
                ->chunk(1000)
                ->each(function ($chunk) {
                    $chunk->each(function ($attestation) {
                        $attestation->update(['current_broker_id' => $this->delivery->broker_id]);
                        $attestation->deliveries()->attach($this->delivery);
                        $attestation->state->transitionTo(Attributed::class);
                    });
                });
        }

        DeliveryItem::insert(array_map(fn ($range) => [
            'range_start' => $range['range_start'],
            'range_end' => $range['range_end'],
            'delivery_id' => $this->delivery->id,
        ], $this->ranges));

        $this->delivery->request->update(['quantity_delivered' => $this->delivery->quantity]);
        $this->delivery->request->state->transitionTo(Delivered::class);

        DB::commit();

        DeliveryDone::dispatch($this->delivery);
    }

    public function failed(\Throwable $exception)
    {
        DeliveryFailed::dispatch($this->delivery, $exception->getMessage());
    }
}
