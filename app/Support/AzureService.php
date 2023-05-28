<?php

namespace Support;

use App\Exceptions\AzureServiceException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Symfony\Component\HttpFoundation\Response;

class AzureService implements AzureServiceInterface
{
    protected $sasToken;
    protected $baseUriStorage;
    protected $apiKey;
    protected $baseUriSearchDataSource;
    protected $apiVersion;
    protected $baseUriSearchIndex;
    protected $baseUriSearchIndexer;
    protected $queryKey;
    protected string $skillsetName;
    private Client $httpClient;

    public function __construct(string $skillSetName = 'saham')
    {
        $this->sasToken = config('services.azure.sas_token');
        $this->baseUriStorage = config('services.azure.base_uri_storage');
        $this->apiKey = config('services.azure.api_key');
        $this->baseUriSearchDataSource = config('services.azure.base_uri_search_datasource');
        $this->apiVersion = config('services.azure.api_version');
        $this->baseUriSearchIndex = config('services.azure.base_uri_search_index');
        $this->baseUriSearchIndexer = config('services.azure.base_uri_search_indexer');
        $this->queryKey = config('services.azure.query_key');
        $this->skillsetName = $skillSetName;
        $this->httpClient = new Client([
            'timeout' => 30000.00,
            'http_errors' => false,
            'verify' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'api-key' => $this->apiKey,
            ],
        ]);
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function copyToAzure(string $containerName, string $path): bool
    {
        if (isDirectoryEmpty($path)) {
            throw new AzureServiceException('[CONTAINER] Aucun fichier à copier sur azure.');
        }

        $blobClient = BlobRestProxy::createBlobService(config('services.azure.credentials'));
        $filePaths = File::allFiles($path);

        foreach ($filePaths as $path) {
            try {
                $file = fopen($path->getPathName(), "rb");
                $blobClient->createBlockBlob($containerName, $path->getFileName(), $file);
            } catch (\Throwable $exception) {
                throw new AzureServiceException(sprintf('[CONTAINER] %s', $exception->getMessage()));
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function createContainer(string $containerName): bool
    {
        $response = $this->httpClient->put("{$this->baseUriStorage}/{$containerName}?restype=container&{$this->sasToken}");

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new AzureServiceException(sprintf('[CONTAINER] %s', (string)$response->getBody()));
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function createDatasource(string $dataSourceName): bool
    {
        $dataSource = $this->getDataSourceTemplate();
        $dataSource['name'] = $dataSourceName;
        $dataSource['container']['name'] = $dataSourceName;
        $dataSource['credentials']['connectionString'] = config('services.azure.credentials');

        $response = $this->httpClient->post("{$this->baseUriSearchDataSource}?api-version={$this->apiVersion}", [
            'body' => json_encode($dataSource),
        ]);

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new AzureServiceException(sprintf('[DATA_SOURCE] %s', (string)$response->getBody()));
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function createIndex(string $indexName): bool
    {
        $index = $this->getIndexTemplate();
        $index['name'] = $indexName;

        $response = $this->httpClient->post("{$this->baseUriSearchIndex}?api-version={$this->apiVersion}", [
            'body' => json_encode($index),
        ]);

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new AzureServiceException(sprintf('[INDEX] %s', (string)$response->getBody()));
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function createIndexer(string $indexerName): bool
    {
        $indexer = $this->getIndexerTemplate();
        $indexer['name'] = $indexerName;
        $indexer['dataSourceName'] = $indexerName;
        $indexer['skillsetName'] = $this->skillsetName;
        $indexer['targetIndexName'] = $indexerName;

        $response = $this->httpClient->post("{$this->baseUriSearchIndexer}?api-version={$this->apiVersion}", [
            'body' => json_encode($indexer),
        ]);

        if (Response::HTTP_CREATED !== $response->getStatusCode()) {
            throw new AzureServiceException(sprintf('[INDEXER] %s', (string)$response->getBody()));
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function runIndexer(string $indexerName): bool
    {
        $response = $this->httpClient->post("{$this->baseUriSearchIndexer}/{$indexerName}/run?api-version={$this->apiVersion}");

        if (Response::HTTP_ACCEPTED !== $response->getStatusCode()) {
            throw new AzureServiceException(sprintf('[INDEXER] %s', (string)$response->getBody()));
        }

        return true;
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     * @throws \Exception
     */
    public function getIndexerStatus(string $indexerName): array
    {
        $response = $this->httpClient->get("{$this->baseUriSearchIndexer}/{$indexerName}/status?api-version={$this->apiVersion}");

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new AzureServiceException(sprintf('[INDEXER] %s', (string)$response->getBody()));
        }

        $result = json_decode($response->getBody(), true);

        if (! isset($result['status']) || ! isset($result['lastResult'])) {
            throw new \Exception("[INDEXER] Impossible de déterminer le status de l'indexer");
        }

        return $result;
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function didIndexerFailed(string $indexerName): bool
    {
        $result = $this->getIndexerStatus($indexerName);

        if (! isset($result['lastResult'])) {
            throw new AzureServiceException("[INDEXER] L'indexer n'a jamais été exécuté.");
        }

        return in_array($result['lastResult']['status'], ["transientFailure", "persistentFailure"]);
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function didIndexerSucceed(string $indexerName): bool
    {
        $result = $this->getIndexerStatus($indexerName);

        if (! isset($result['lastResult'])) {
            throw new AzureServiceException("[INDEXER] L'indexer n'a jamais été exécuté.");
        }

        return $result['lastResult']['status'] === "success";
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function isIndexerRunning(string $indexerName): bool
    {
        $result = $this->getIndexerStatus($indexerName);

        return $result['status'] === "inProgress" || $result['lastResult']['status'] === "inProgress";
    }

    /**
     * @inheritDoc
     * @throws AzureServiceException
     */
    public function queryIndexer(string $indexName): array
    {
        $top = '$top';

        $response = $this->httpClient->get(
            "{$this->baseUriSearchIndex}/{$indexName}/docs?api-version={$this->apiVersion}&$top=1000",
            [
                'headers' => [
                    'api-key' => $this->queryKey,
                ],
            ]
        );

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new AzureServiceException(sprintf('[INDEXER] %s', (string)$response->getBody()));
        }

        return json_decode($response->getBody(), true)['value'];
    }

    /**
     * @inheritDoc
     */
    public function deleteIndexer(string $indexerName): bool
    {
        try {
            $response = $this->httpClient->delete("{$this->baseUriSearchIndexer}/{$indexerName}?api-version={$this->apiVersion}");

            if (Response::HTTP_NO_CONTENT !== $response->getStatusCode()) {
                throw new AzureServiceException(sprintf('[INDEXER] %s', (string)$response->getBody()));
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteIndex(string $indexName): bool
    {
        try {
            $response = $this->httpClient->delete("{$this->baseUriSearchIndex}/{$indexName}?api-version={$this->apiVersion}");

            if (Response::HTTP_NO_CONTENT !== $response->getStatusCode()) {
                throw new AzureServiceException(sprintf('[INDEX] %s', (string)$response->getBody()));
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteDataSource(string $dataSourceName): bool
    {
        try {
            $response = $this->httpClient->delete("{$this->baseUriSearchDataSource}/{$dataSourceName}?api-version={$this->apiVersion}");

            if (Response::HTTP_NO_CONTENT !== $response->getStatusCode()) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteContainer(string $containerName): bool
    {
        try {
            $response = $this->httpClient->delete("{$this->baseUriStorage}/{$containerName}?restype=container&{$this->sasToken}");

            if (Response::HTTP_ACCEPTED !== $response->getStatusCode()) {
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function withSkillset(string $skillsetName): self
    {
        $this->skillsetName = $skillsetName;

        return $this;
    }

    private function getIndexTemplate(): array
    {
        return [
            "@odata.context" => 'https://basicsearch.search.windows.net/$metadata#indexes/$entity',
            "@odata.etag" => "\"0x8D740DCC1E21CDC\"",
            "name" => "[IndexName]",
            "defaultScoringProfile" => null,
            "fields" => [
                [
                    "name" => "id",
                    "type"=> "Edm.String",
                    "searchable"=> true,
                    "filterable"=> false,
                    "retrievable"=> true,
                    "sortable"=> true,
                    "facetable"=> false,
                    "key"=> true,
                    "indexAnalyzer"=> null,
                    "searchAnalyzer"=> null,
                    "analyzer"=> null,
                    "synonymMaps"=> []
                ],
                [
                    "name"=> "metadata_storage_name",
                    "type"=> "Edm.String",
                    "searchable"=> false,
                    "filterable"=> false,
                    "retrievable"=> true,
                    "sortable"=> false,
                    "facetable"=> false,
                    "key"=> false,
                    "indexAnalyzer"=> null,
                    "searchAnalyzer"=> null,
                    "analyzer"=> null,
                    "synonymMaps"=> []
                ],
                [
                    "name"=> "content",
                    "type"=> "Edm.String",
                    "searchable"=> true,
                    "filterable"=> false,
                    "retrievable"=> true,
                    "sortable"=> false,
                    "facetable"=> false,
                    "key"=> false,
                    "indexAnalyzer"=> null,
                    "searchAnalyzer"=> null,
                    "analyzer"=> null,
                    "synonymMaps"=> []
                ],
                [
                    "name"=> "merged_content",
                    "type"=> "Edm.String",
                    "searchable"=> true,
                    "filterable"=> false,
                    "retrievable"=> true,
                    "sortable"=> false,
                    "facetable"=> false,
                    "key"=> false,
                    "indexAnalyzer"=> null,
                    "searchAnalyzer"=> null,
                    "analyzer"=> "standard.lucene",
                    "synonymMaps"=> []
                ],
                [
                    "name"=> "text",
                    "type"=> "Collection(Edm.String)",
                    "searchable"=> true,
                    "filterable"=> false,
                    "retrievable"=> true,
                    "sortable"=> false,
                    "facetable"=> false,
                    "key"=> false,
                    "indexAnalyzer"=> null,
                    "searchAnalyzer"=> null,
                    "analyzer"=> "standard.lucene",
                    "synonymMaps"=> []
                ],
                [
                    "name"=> "merged_text",
                    "type"=> "Edm.String",
                    "searchable"=> true,
                    "filterable"=> false,
                    "retrievable"=> true,
                    "sortable"=> false,
                    "facetable"=> false,
                    "key"=> false,
                    "indexAnalyzer"=> null,
                    "searchAnalyzer"=> null,
                    "analyzer"=> "standard.lucene",
                    "synonymMaps"=> []
                ]
            ],
            "scoringProfiles"=> [],
            "corsOptions"=> null,
            "suggesters"=> [],
            "analyzers"=> [],
            "tokenizers"=> [],
            "tokenFilters"=> [],
            "charFilters"=> []
        ];
    }

    private function getIndexerTemplate(): array
    {
        return [
            "name" => "[IndexerName]",
            "description" => "",
            "dataSourceName" => "[dataSourceName]",
            "skillsetName" => "[skillsetName]",
            "targetIndexName" => "[IndexName]",
            "disabled" => null,
            "schedule" => null,
            "parameters" => [
                "batchSize" => null,
                "maxFailedItems" => null,
                "maxFailedItemsPerBatch" => null,
                "base64EncodeKeys" => false,
                "configuration" => [
                    "dataToExtract" => "contentAndMetadata",
                    "parsingMode" => "default",
                    "firstLineContainsHeaders"=> false,
                    "delimitedTextDelimiter" => ",",
                    "imageAction" => "generateNormalizedImages"
                ]
            ],
            "fieldMappings" => [
                [
                    "sourceFieldName" => "metadata_storage_path",
                    "targetFieldName" => "metadata_storage_path",
                    "mappingFunction" => [
                        "name" => "base64Encode",
                        "parameters" => null
                        ]
                    ]
            ],
            "outputFieldMappings" => [
                [
                    "sourceFieldName" => "/document/merged_content",
                    "targetFieldName" => "merged_content",
                    "mappingFunction" => null
                ],
                [
                    "sourceFieldName" => "/document/normalized_images/*/text",
                    "targetFieldName" => "text",
                    "mappingFunction" => null
                ],
                [
                    "sourceFieldName" => "/document/merged_text",
                    "targetFieldName" => "merged_text",
                    "mappingFunction" => null
                ],
                [
                    "sourceFieldName" => "/document/content",
                    "targetFieldName" => "content",
                    "mappingFunction" => null
                    ]
            ]
        ];
    }

    private function getDataSourceTemplate(): array
    {
        return [
            "@odata.context" => 'https://basicsearch.search.windows.net/$metadata#datasources/$entity',
            "@odata.etag" => "\"0x8D740F8431B93A2\"",
            "name" => "[DatasourceName]",
            "type" => "azureblob",
            "credentials" => [
                "connectionString" => config('services.azure.credentials')
            ],
            "container" => [
                "name" => "[ContainerName]",
                "query" => null
            ],
            "dataChangeDetectionPolicy" => null,
            "dataDeletionDetectionPolicy" => null
        ];
    }
}
