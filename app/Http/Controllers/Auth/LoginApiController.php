<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Routing\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginApiController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function getUsuarioAutenticado()
    {
        try{
            if (! $usuario = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } 
        catch (TokenExpiredException $e){
            return response()->json(['token_expired']);
        }
        catch (TokenInvalidException $e){
            return response()->json(['token_invalid']);
        }
        catch (JWTException $e){
            return response()->json(['token_absent']);
        }

        return response()->json(compact('usuario'));
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refreshToken()
    {
        if(!$token = JWTAuth::getToken()){
            return response()->json(['error' => 'token_not_send'], 401);
        }
        return $this->respondWithToken(JWTAuth::refresh($token));
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 1
        ]);
    }
}
