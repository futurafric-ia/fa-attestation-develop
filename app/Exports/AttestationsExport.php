<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttestationsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    private \Illuminate\Database\Eloquent\Builder $query;

    public function __construct(\Illuminate\Database\Eloquent\Builder $query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
           "Numéro d'attestation",
           "Type d'imprimés",
           'Statut'
       ];
    }

    public function map($row): array
    {
        return [
            $row->attestation_number,
            $row->attestationType->name,
            $row->state->label(),
        ];
    }
}
