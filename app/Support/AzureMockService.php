<?php

namespace Support;

class AzureMockService implements AzureServiceInterface
{
    public function copyToAzure(string $containerName, string $filePath): bool
    {
        return true;
    }

    public function createContainer(string $containerName): bool
    {
        return true;
    }

    public function createDatasource(string $dataSourceName): bool
    {
        return true;
    }

    public function createIndex(string $indexName): bool
    {
        return true;
    }

    public function createIndexer(string $indexerName): bool
    {
        return true;
    }

    public function runIndexer(string $indexerName): bool
    {
        return true;
    }

    public function getIndexerStatus(string $indexerName): array
    {
        return ["status" => "success"];
    }

    public function didIndexerFailed(string $indexerName): bool
    {
        return false;
    }

    public function didIndexerSucceed(string $indexerName): bool
    {
        return true;
    }

    public function isIndexerRunning(string $indexerName): bool
    {
        return false;
    }

    public function queryIndexer(string $indexName): array
    {
        $results = [];

        for ($i = 0; $i < 10; ++$i) {
            $number = random_int(866412, 999999);
            $results[] = [
                'metadata_storage_name' => '',
                'text' => [
                    "ASSURE : INSURED : ALMAO BP 3623 ABIDJAN 01 Police No 1001 /4000000117 POLICY NO : DU _01/01/2015 FROM 0Q:90 AU 31/12/2015 TO VEHICULE (Genrevehicule Utilitaire 23:59 VEHICULE (Type) : MARQUE NISSAN No 8022{$number} MAKE : IMMATRICULATION OU NO DE CHASSIS REGISTRATION OR CHASSIS NO 21 7931 EY OT SAHAM Asturnage 8022866412 C.TID 0 CAT2",
                ],
            ];
        }

        return $results;
    }

    public function deleteIndexer(string $indexerName): bool
    {
        return true;
    }

    public function deleteIndex(string $indexName): bool
    {
        return true;
    }

    public function deleteDataSource(string $dataSourceName): bool
    {
        return true;
    }

    public function deleteContainer(string $containerName): bool
    {
        return true;
    }

    public function withSkillset(string $skillsetName): self
    {
        return $this;
    }
}
