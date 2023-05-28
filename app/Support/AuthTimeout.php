<?php

namespace Support;

use Illuminate\Auth\AuthManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Session\SessionManager;

class AuthTimeout
{
    /**
     * The Authentication Manager.
     *
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * The Event Dispatcher.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $event;

    /**
     * The Session Manager.
     *
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * La clé de session.
     *
     * @var string
     */
    protected $session_name;

    /**
     * @param  \Illuminate\Auth\AuthManager  $auth
     * @param  \Illuminate\Events\Dispatcher  $event
     * @param  \Illuminate\Session\SessionManager  $session
     */
    public function __construct(AuthManager $auth, Dispatcher $event, SessionManager $session)
    {
        $this->auth = $auth;
        $this->event = $event;
        $this->session = $session;

        $this->session_name = config('auth-timeout.session');
        $this->timeout = config('auth-timeout.timeout') * 60;
    }

    /**
     * Initialise la session de temps d'inactivité
     */
    public function init()
    {
        if (! $this->session->get($this->session_name)) {
            $this->session->put($this->session_name, time());
        }
    }

    /**
     * Verifies si la session de l'utilisateur a expiré. Retournes `true` si c'est le cas et `false` si non.
     */
    public function check($guard = null)
    {
        // Passer le check si il n'y a pas d'utilisateur connecté
        if ($this->auth->guard($guard)->guest()) {
            return true;
        }

        // Ici, on verifie si l'utilisateur est toujours dans le temps d'inactivité autorisé
        if ((time() - (int) $this->session->get($this->session_name)) < $this->timeout) {
            return false;
        }

        // Ici, nous savons que l'utilisateur a atteint le temps maximum d'inactivité.
        // Nous allons donc invalider la session, le déconnecter et retourner `false`.
        $user = $this->auth->guard($guard)->user();

        $this->auth->guard($guard)->logout();
        $this->session->forget($this->session_name);

        return true;
    }

    /**
     * Réinitialise la session de temps d'inactivité
     */
    public function reset()
    {
        $this->session->put($this->session_name, time());
    }
}
