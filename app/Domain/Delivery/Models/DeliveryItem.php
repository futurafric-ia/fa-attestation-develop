<?php

namespace Domain\Delivery\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'delivered_at' => 'datetime:d/m/Y H:i:s',
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];
}
