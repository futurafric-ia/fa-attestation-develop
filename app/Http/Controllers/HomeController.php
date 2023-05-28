<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route(homeRoute());
    }

    public function leaveImpersonate()
    {
        Auth::user()->leaveImpersonation();

        return redirect()->route(homeRoute());
    }
}
