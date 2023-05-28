<?php

namespace Support;

interface AzureServiceInterface
{
    /**
     * @param string $containerName
     * @param string $filePath
     * @return bool
     */
    public function copyToAzure(string $containerName, string $filePath): bool;

    /**
     * @param string $containerName
     * @return bool
     */
    public function createContainer(string $containerName): bool;

    /**
     * @param string $dataSourceName
     * @return bool
     */
    public function createDatasource(string $dataSourceName): bool;

    /**
     * @param string $indexName
     * @return bool
     */
    public function createIndex(string $indexName): bool;

    /**
     * @param string $indexerName
     * @return bool
     */
    public function createIndexer(string $indexerName): bool;

    /**
     * @param string $indexerName
     * @return bool
     */
    public function runIndexer(string $indexerName): bool;

    /**
     * @param string $indexerName
     * @return array
     */
    public function getIndexerStatus(string $indexerName): array;

    /**
     * @param string $indexerName
     * @return bool
     */
    public function didIndexerFailed(string $indexerName): bool;

    /**
     * @param string $indexerName
     * @return bool
     */
    public function didIndexerSucceed(string $indexerName): bool;

    /**
     * @param string $indexerName
     * @return bool
     */
    public function isIndexerRunning(string $indexerName): bool;

    /**
     * @param string $indexName
     * @return array
     */
    public function queryIndexer(string $indexName): array;

    /**
     * @param string $indexerName
     * @return bool
     */
    public function deleteIndexer(string $indexerName): bool;

    /**
     * @param string $indexName
     * @return bool
     */
    public function deleteIndex(string $indexName): bool;

    /**
     * @param string $dataSourceName
     * @return bool
     */
    public function deleteDataSource(string $dataSourceName): bool;

    /**
     * @param string $containerName
     * @return bool
     */
    public function deleteContainer(string $containerName): bool;

    /**
     * @param string $skillsetName
     * @return $this
     */
    public function withSkillset(string $skillsetName): self;
}
