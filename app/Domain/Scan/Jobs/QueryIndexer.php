<?php

namespace Domain\Scan\Jobs;

use App\Exceptions\AzureServiceException;
use Domain\Scan\Models\Scan;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Support\AzureServiceInterface;

class QueryIndexer implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    protected $scanId;

    protected const WAIT_TIME = 5;

    public function __construct($scanId)
    {
        $this->scanId = $scanId;
    }

    /**
     * @param AzureServiceInterface $azureService
     * @throws AzureServiceException
     */
    public function handle(AzureServiceInterface $azureService)
    {
        $scan = Scan::find($this->scanId);

        // Dans les cas ou le status de l'indexer n'a pu être determiné pour cause d'indisponibilité.
        try {
            if (! $azureService->isIndexerRunning($scan->resource_name)) {
                $azureService->runIndexer($scan->resource_name);
            }
        } catch (\Exception $exception) {
            sleep(self::WAIT_TIME);

            if (! $azureService->isIndexerRunning($scan->resource_name)) {
                $azureService->runIndexer($scan->resource_name);
            }
        }

        $indexerIsRunning = true;
        $queryResults = null;
        $exceptionMessage = '';

        sleep(self::WAIT_TIME); // Donne le temps à l'indexer de terminer l'éxécution

        while ($indexerIsRunning) {
            try {
                $indexerIsRunning = $azureService->isIndexerRunning($scan->resource_name);

                if ($indexerIsRunning) {
                    throw new \Exception("[INDEXER] L'indexer est en toujours cours d'exécution.");
                }

                if ($azureService->didIndexerFailed($scan->resource_name)) {
                    throw new AzureServiceException("[INDEXER] L'exécution de indexer ({$scan->resource_name}) a échoué.");
                }

                $queryResults = $azureService->queryIndexer($scan->resource_name);
            } catch (\Throwable $exception) {
                $exceptionMessage = $exception->getMessage();

                if ($indexerIsRunning) {
                    sleep(self::WAIT_TIME * 2);
                }
            }
        }

        if (null === $queryResults) {
            throw new AzureServiceException($exceptionMessage);
        }

        if (is_array($queryResults) && empty($queryResults)) {
            throw new AzureServiceException("[INDEXER] L'indexer n'a renvoyé aucun résultat.");
        }

        $scan->update(['query_results' => $queryResults]);
    }
}
