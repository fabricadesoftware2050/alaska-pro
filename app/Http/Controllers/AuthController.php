<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        try {
            $credentials = request(['email', 'password']);

        // Agregar más datos al token
        $user = User::where('email', $credentials['email'])->first();;
        if (!$user) {
            return response()->json(['error' => 'Unauthorized','message' => 'Datos de acceso incorrectos'], 401);
        }
        $customClaims = [
            'role' => $user->role,
            'company_position' => $user->company_position,
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'expires_in' => auth()->factory()->getTTL() * 60

        ];
        if (! $token = auth()->claims($customClaims)->attempt($credentials)) {
        //if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized','message' => 'No se logró generar el token'], 401);
        }

        return $this->respondWithToken($token);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Login failed', 'message' => $ex->getMessage()], 500);
            //throw $th;
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to logout', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
