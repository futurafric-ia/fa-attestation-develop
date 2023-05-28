<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request)
    {
        $errors = $this->validateLogin($request->all())->errors();

        if (! empty($errors->all())) {
            return response()->json($errors->toArray(), Response::HTTP_UNAUTHORIZED);
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($this->guard()->user()->active) {
                return $this->sendLoginResponse($request);
            } else {
                $error = [
                    'source' => ['email' => $request->get('email')],
                    'email' => ['Compte non actif'],
                ];

                return response()->json($error, Response::HTTP_FORBIDDEN);
            }
        }

        $this->incrementLoginAttempts($request);

        $error = [
            'email' => ['Identifiants incorrects. Veuillez vérifier l\'adresse E-mail ou le mot de passe et réessayer à nouveau.'],
        ];

        return response()->json($error, Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([]);
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user());
    }

    protected function authenticated(Request $request, $user)
    {
        return response()->json([
            'data' => [
                'access_token' => $user->api_token,
                'token_type' => 'Bearer',
                'email' => $user->email,
                'id' => $user->id,
                'token' => 'Bearer ' . $user->api_token,
            ],
        ]);
    }

    protected function validateLogin(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);
    }
}
