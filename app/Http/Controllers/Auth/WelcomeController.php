<?php

namespace App\Http\Controllers\Auth;

use Spatie\WelcomeNotification\WelcomeController as BaseWelcomeController;

class WelcomeController extends BaseWelcomeController
{
    public function redirectTo()
    {
        return '/';
    }
}
