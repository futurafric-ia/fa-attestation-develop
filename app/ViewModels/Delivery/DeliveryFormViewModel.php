<?php

namespace App\ViewModels\Delivery;

use Domain\Delivery\Models\Delivery;
use Domain\Request\Models\Request;
use Spatie\ViewModels\ViewModel;

class DeliveryFormViewModel extends ViewModel
{
    private ?Delivery $delivery;

    private Request $request;

    public function __construct(Request $request, ?Delivery $delivery = null)
    {
        $this->delivery = $delivery;
        $this->request = $request;
    }

    public function delivery(): Delivery
    {
        return $this->delivery ?? new Delivery();
    }

    public function request(): Request
    {
        return $this->request ?? new Request();
    }
}
