<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class ToBeLoggedOut.
 */
class ToBeLoggedOut
{
    /**
     * @param $request
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        // If the user is to be logged out
        if ($request->user() && $request->user()->to_be_logged_out) {
            // Make sure they can log back in next session
            $request->user()->update(['to_be_logged_out' => false]);

            // Kill the current session and force back to the login screen
            session()->flush();
            auth()->logout();

            return redirect()->route('login');
        }

        return $next($request);
    }
}
