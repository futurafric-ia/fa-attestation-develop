<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Session Name
    |--------------------------------------------------------------------------
    |
    | Ici, vous pouvez modifier le nom de session utilisé pour identifier
    | si l'utilisateur a atteint le délai de temporisation.
    */

    'session' => 'last_activity_time',

    /*
    |--------------------------------------------------------------------------
    | Session Timeout
    |--------------------------------------------------------------------------
    |
    | Ici, vous pouvez spécifier le nombre de minutes qu'un utilisateur est autorisé à
    | rester inactif avant qu'il ne soit déconnecté.
    |
    */

    'timeout' => env('AUTH_SESSION_TIMEOUT', 60),

];
