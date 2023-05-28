<?php

namespace Domain\Reports\Actions;

use Domain\User\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Spatie\QueueableAction\QueueableAction;

abstract class Exporter
{
    use QueueableAction;

    protected $exportPath;

    public function __construct()
    {
        $this->exportPath = Storage::disk('public')->path('exports');
    }

    public function execute(string $fileName, array $filters, ?User $user = null)
    {
        $exportFileName = $this->exportPath . '/' . $fileName;
        $extension = File::extension($fileName);

        if (! in_array($extension, ['csv', 'xls', 'xlsx', 'pdf'])) {
            throw new InvalidArgumentException('Type de fichier non supporté !');
        }

        if ($extension === 'pdf') {
            $this->exportPdf($exportFileName, $filters, $user);
        }

        if (in_array($extension, ['csv', 'xls', 'xlsx'], true)) {
            $this->exportExcel($exportFileName, $filters, $user);
        }
    }

    protected function exportExcel(string $fileName, array $filters, ?User $user)
    {
    }

    protected function exportPdf(string $fileName, array $filters, ?User $user)
    {
    }
}
