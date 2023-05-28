<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function __invoke(string $id, string $hash): RedirectResponse
    {
        $user = Auth::user();

        if (! hash_equals((string) $id, (string) $user->getKey())) {
            throw new AuthorizationException();
        }

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException();
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(route(homeRoute()));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified(Auth::user()));
        }

        return redirect(route(homeRoute()));
    }
}
