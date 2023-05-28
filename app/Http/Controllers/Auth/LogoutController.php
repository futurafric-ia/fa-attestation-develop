<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use AuthenticatesUsers;

    public function __invoke(Request $request)
    {
        return $this->logout($request);
    }

    public function loggedOut(Request $request)
    {
        return redirect()->to('/login');
    }
}
