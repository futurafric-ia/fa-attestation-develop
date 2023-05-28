<?php

namespace Domain\Scan\Jobs;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\Parsers\AttestationParserFactory;
use Domain\Attestation\States\Attributed;
use Domain\Attestation\States\Available;
use Domain\Attestation\States\Used;
use Domain\Scan\Models\Scan;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ApplyIndexerResults implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    protected $scanId;

    public function __construct($scanId)
    {
        $this->scanId = $scanId;
    }

    public function handle()
    {
        $scan = Scan::find($this->scanId);

        DB::beginTransaction();

        $queryResults = $scan->query_results;
        $parser = AttestationParserFactory::make($scan->attestation_type_id);
        $parserResults = [];
        $mismatchesCount = 0;

        foreach ($queryResults as $queryResult) {
            $text = trim($queryResult['text'][0] ?? '');
            $parserResult = $parser->parse($text);
            $parserResults[] = $parserResult;
            $payload = array_merge($parserResult, [
                'image_path' => "{$scan->resource_name}/{$queryResult['metadata_storage_name']}",
                'content' => $text,
            ]);

            $attestation = Attestation::query()->withoutGlobalScopes()->firstWhere([
                'attestation_number' => $parserResult['attestation_number'],
                'attestation_type_id' => $scan->attestation_type_id,
                'current_broker_id' => $scan->broker->id,
            ]);

            if ($attestation) {
                $attestation->update($payload + ['last_scan_id' => $scan->id]);
                $attestation->scans()->attach($scan);

                if ($attestation->anterior && $attestation->state->equals([Available::class])) {
                    $attestation->state->transitionTo(Attributed::class);
                }

                if ($attestation->state->canTransitionTo(Used::class)) {
                    $attestation->state->transitionTo(Used::class);
                }
            } else {
                $scan->mismatches()->create($payload + ['attestation_type_id' => $scan->attestation_type_id]);
                ++$mismatchesCount;
            }
        }

        $scan->update(['mismatches_count' => $mismatchesCount, 'parser_results' => $parserResults]);

        DB::commit();
    }
}
