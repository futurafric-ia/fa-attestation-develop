<?php

namespace Domain\Scan\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Support\AzureServiceInterface;

class ReleaseAzureResources implements ShouldQueue
{
    protected AzureServiceInterface $azureService;

    public function __construct(AzureServiceInterface $azureService)
    {
        $this->azureService = $azureService;
    }

    public function handle($event)
    {
        if (! $event->scan->isOcr()) {
            return;
        }

        if (! $event->scan->resource_name) {
            return;
        }

        $resourceName = $event->scan->resource_name;
        $this->azureService->deleteContainer($resourceName);
        $this->azureService->deleteDatasource($resourceName);
        $this->azureService->deleteIndex($resourceName);
        $this->azureService->deleteIndexer($resourceName);
    }
}
