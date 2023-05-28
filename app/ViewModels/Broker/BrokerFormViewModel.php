<?php

namespace App\ViewModels\Broker;

use Domain\Broker\Models\Broker;
use Domain\Department\Models\Department;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class BrokerFormViewModel extends ViewModel
{
    private ?Broker $broker;

    public function __construct(?Broker $broker = null)
    {
        $this->broker = $broker;
    }

    public function broker(): ?Broker
    {
        return $this->broker;// ?? new Broker();
    }

    public function departments(): Collection
    {
        return Department::query()->pluck('name', 'id');
    }
}
