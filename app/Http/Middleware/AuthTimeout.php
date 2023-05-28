<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthManager;
use Support\AuthTimeout as AuthTimeoutHelper;

class AuthTimeout
{
    /**
     * The Authentication Manager.
     *
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * The AuthTimeout instance.
     *
     * @var \Support\AuthTimeout
     */
    protected $authTimeout;

    public function __construct(AuthManager $auth, AuthTimeoutHelper $authTimeout)
    {
        $this->auth = $auth;
        $this->authTimeout = $authTimeout;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $guard
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Passer la verification si l'utlisateur n'est pas authentifié
        if ($this->auth->guard($guard)->guest()) {
            return $next($request);
        }

        $this->authTimeout->init();

        if ($this->authTimeout->check($guard)) {
            throw new AuthenticationException('Session d\'authentification expiré.', [$guard], $this->redirectTo($request, $guard));
        }

        $this->authTimeout->reset();

        return $next($request);
    }

    /**
     * Retournes l'adresse de redirection après la déconnection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed    $guard
     *
     * @return string|null
     */
    protected function redirectTo($request, $guard)
    {
        return route('login');
    }
}
