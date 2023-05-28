<?php

namespace App\Http\Livewire\Reports;

use Illuminate\Support\Carbon;
use Livewire\Component;

class RequestsReport extends Component
{
    public $dateRanges = [
        'today' => "Aujourd'hui",
        'this_week' => 'Cette semaine',
        'this_month' => 'Ce mois',
        'this_quarter' => 'Ce trimestre',
        'this_year' => 'Cette année',
        'yesterday' => 'Hier',
        'last_week' => 'Semaine passée',
        'last_month' => 'Mois passé',
        'last_quarter' => 'Trimestre passé',
        'last_year' => 'Année passée',
        'custom' => 'Autre',
    ];

    public $state = [
        'in_the_period' => 'this_month',
        'between_date' => null,
    ];

    public $filters = [];

    public function mount()
    {
        $this->updateReport();
    }

    public function updateReport()
    {
        $this->filters = array_filter($this->state, function ($item) {
            return $item != null && $item !== 'custom';
        });

        if (isset($this->filters['between_date'])) {
            $dateParts = explode(' ', $this->filters['between_date']);

            if (count($dateParts) === 3) {
                try {
                    $this->filters['between_date'] = implode(',', [Carbon::parse($dateParts[0])->format('Y-m-d'), Carbon::parse($dateParts[2])->format('Y-m-d')]);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.reports.requests-report');
    }
}
