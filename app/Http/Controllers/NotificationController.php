<?php

namespace App\Http\Controllers;

use Domain\Request\Models\Request;

class NotificationController
{
    public function markAllAsRead(\Illuminate\Http\Request $request)
    {
        $request->user()->notifications->markAsRead();

        return back();
    }

    public function markAsRead(\Illuminate\Http\Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();

            if (strpos($notification->type, 'Request') !== false && ! Request::whereUuid($notification->data['request_id'])->first()) {
                session()->flash('info', "Cette demande n'a pas été retrouvée.");

                return redirect()->route('request.index');
            }

            return redirect()->intended($request->input('redirectTo', '/'));
        }

        return back();
    }
}
