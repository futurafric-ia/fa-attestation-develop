<?php

namespace Domain\Scan\Jobs;

use Domain\Scan\Models\Scan;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Support\AzureServiceInterface;

class SetupAzure implements ShouldQueue
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

    public function handle(AzureServiceInterface $azureService)
    {
        $scan = Scan::find($this->scanId);

        $azureService->createContainer($scan->resource_name);
        $azureService->copyToAzure($scan->resource_name, $scan->files_paths['output_path']);
        $azureService->createDatasource($scan->resource_name);
        $azureService->createIndex($scan->resource_name);
        $azureService->createIndexer($scan->resource_name);
    }
}
