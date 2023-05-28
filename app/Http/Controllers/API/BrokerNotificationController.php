<?php

namespace App\Http\Controllers\API;

use Symfony\Component\HttpFoundation\Response;

class BrokerNotificationController
{
    public function index()
    {
        return response()->json(['data' => auth()->user()->unreadNotifications]);
    }

    public function markAsRead($notification)
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
