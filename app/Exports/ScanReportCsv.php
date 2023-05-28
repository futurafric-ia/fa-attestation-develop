<?php

namespace App\Exports;

use Domain\Scan\Models\Scan;
use Maatwebsite\Excel\Concerns\FromCollection;

class ScanReportCsv implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
      return Scan::orderByAsc('id')->all();
    }

}
