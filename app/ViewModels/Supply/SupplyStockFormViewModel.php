<?php

namespace App\ViewModels\Supply;

use Domain\Attestation\Models\AttestationType;
use Domain\Supply\Models\Supplier;
use Domain\Supply\Models\Supply;
use Spatie\ViewModels\ViewModel;

class SupplyStockFormViewModel extends ViewModel
{
    protected $supply;

    public function __construct(Supply $supply = null)
    {
        $this->supply = $supply;
    }

    public function supply(): Supply
    {
        return $this->supply ?? new Supply();
    }

    public function suppliers(): array
    {
        return Supplier::query()->pluck('name', 'id')->toArray();
    }

    public function attestationTypes(): array
    {
        return AttestationType::query()->pluck('name', 'id')->toArray();
    }

    public function supplyCode(): string
    {
        $current_date = date('dmY');
        $supply_count = Supply::query()
            ->where('code', 'like', "{$current_date}%")
            ->count();

        $code = "{$current_date}-" . (++$supply_count);

        return $code;
    }
}
