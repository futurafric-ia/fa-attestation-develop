<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DeliveryResource;

class BrokerDeliveryController
{
    public function index()
    {
        return DeliveryResource::collection(broker()->deliveries()->paginate(25))->response();
    }

    public function show($delivery)
    {
        return (new DeliveryResource(broker()->deliveries()->with('items')->where('id', $delivery)->firstOrFail()))->response();
    }
}
