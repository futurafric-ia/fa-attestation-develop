<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuppliesExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    private Builder $query;

    public function __construct(Builder $query)
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
            'Code',
            'Date de reception',
            "Date d'approvisionnement",
            'Type',
            'Début série',
            'Fin série',
            'Quantité'
        ];
    }

    public function map($supply): array
    {
        return [
            $supply->code,
            $supply->received_at->format('d/m/Y'),
            $supply->created_at->format('d/m/Y H:i'),
            $supply->attestationType->name,
            $supply->range_start,
            $supply->range_end,
            $supply->quantity,
        ];
    }
}
