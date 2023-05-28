<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return back()->with('error', 'Votre session a expiré. Veuillez réessayer SVP ...');
        }

        if ($exception instanceof AuthenticationException) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Non authentifié.'], 403);
            }
        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Vous n\'êtes pas autorisé à effectuer cette action.'], 403);
            }

            return redirect()
                ->route(homeRoute())
                ->with('error', "Vous n'êtes pas autorisé à effectuer cette action");
        }

        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'L\'enregistrement n\'a pas été retrouvé.'], Response::HTTP_NOT_FOUND);
            }
        }

        return parent::render($request, $exception);
    }
}
